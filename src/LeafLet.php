<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\leaflet;

use dosamigos\leaflet\controls\Control;
use dosamigos\leaflet\layers\Layer;
use dosamigos\leaflet\layers\LayerGroup;
use dosamigos\leaflet\layers\TileLayer;
use dosamigos\leaflet\layers\Polygon;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\widgets\Map;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

/**
 * Class LeafLet
 * @package dosamigos\leaflet
 */

/**
 * @property LatLng $center
 *
 */
class LeafLet extends Component
{
    // JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK
    const JSON_OPTIONS = 352;

    /**
     * @var integer a counter used to generate [[name]] for layers.
     * @internal
     */
    public static int $counter = 0;

    /**
     * @var string the prefix to the automatically generated object names.
     * @see [[generateName()]]
     */
    public static string $autoNamePrefix = 'l';

    /**
     * @var string the name to give to the variable. The name of the map specified on the
     * [[TileLayer]] component overrides this one.
     */
    public string $name = 'map';

    /**
     * @var int the zoom level of the map
     */
    public int $zoom = 13;

    /**
     * @var array the options for the underlying LeafLetJs JS component.
     * Please refer to the LeafLetJs api reference for possible
     * [options](http://leafletjs.com/reference.html).
     */
    public array $clientOptions = [];

    /**
     * @var array the event handlers for the underlying LeafletJs JS plugin.
     * Please refer to the LeafLetJs js api object options for possible events.
     */
    public array $clientEvents = [];

    /**
     * @var Layer[] holding ui layers (do not confuse with map layers, these are markers, popups, polygons, etc)
     */
    private array $_layers = [];

    /**
     * @var LayerGroup[] holding layer groups
     */
    private array $_layerGroups = [];
    
    /**
     * @var LatLng sets the center of the map
     */
    private ?LatLng $_center = null;

    /**
     * Returns the center of the map.
     * @return LatLng|null center of the map.
     */
    public function getCenter(): ?LatLng
    {
        return $this->_center;
    }

    /**
     * Sets the center of the map.
     *
     * @param LatLng $value center of the map.
     */
    public function setCenter(LatLng $value): void
    {
        $this->_center = $value;
    }

    /**
     * @var Control[] holding controls to be added to the map.
     */
    private array $_controls = [];

    /**
     * @param \dosamigos\leaflet\controls\Control[] $controls
     *
     * @throws \yii\base\InvalidArgumentException
     */
    public function setControls(array $controls): void
    {
        foreach ($controls as $control) {
            if (!($control instanceof Control)) {
                throw new InvalidArgumentException("All controls must be of type Control.");
            }
        }
        $this->_controls = $controls;
    }

    /**
     * @return \dosamigos\leaflet\controls\Control[]
     */
    public function getControls(): array
    {
        return $this->_controls;
    }

    /**
     * @param Control $control
     */
    public function addControl(Control $control): void
    {
        $this->_controls[] = $control;
    }

    /**
     * @var \dosamigos\leaflet\layers\TileLayer
     */
    private ?TileLayer $_tileLayer = null;

    /**
     * @param \dosamigos\leaflet\layers\TileLayer $tileLayer
     *
     * @return static the component itself
     */
    public function setTileLayer(TileLayer $tileLayer): self
    {
        if (!empty($tileLayer->map) && strcmp($tileLayer->map, $this->name) !== 0) {
            $this->name = $tileLayer->map;
        }
        if (empty($tileLayer->map)) {
            $tileLayer->map = $this->name;
        }
        $this->_tileLayer = $tileLayer;

        return $this;
    }

    /**
     * @return \dosamigos\leaflet\layers\TileLayer
     */
    public function getTileLayer(): ?TileLayer
    {
        return $this->_tileLayer;
    }

    /**
     * @var array holds the js script lines to be registered.
     */
    private array $_js = [];

    /**
     * @param string|array $js custom javascript code to be registered.
     * *Warning*: This method overrides any previous settings.
     *
     * @return static the component itself
     */
    public function setJs($js): self
    {
        $this->_js = is_array($js) ? $js : [$js];
        return $this;
    }

    /**
     * @param string $js appends javascript code to be registered.
     *
     * @return static the component itself
     */
    public function appendJs($js): self
    {
        $this->_js[] = $js;
        return $this;
    }

    /**
     * @return array the queued javascript code to be registered.
     * *Warning*: This method does not include map initialization.
     */
    public function getJs(): array
    {
        $js = [];
        foreach ($this->getLayers() as $layer) {

            if ($layer instanceof Polygon) {
                $layerJs = $layer->encode();
                $insertAtTheBottom = $layer->insertAtTheBottom ? 'true' : 'false';
                $js[] = "$this->name.addLayer($layerJs, $insertAtTheBottom);";
                continue;
            }
            $layer->map = $this->name;
            $js[] = $layer->encode();
        }
        $groups = $this->getEncodedLayerGroups($this->getLayerGroups());
        $controls = $this->getEncodedControls($this->getControls());
        $plugins = $this->getEncodedPlugins($this->getPlugins()->getInstalledPlugins());
        $js = ArrayHelper::merge($js, $groups);
        $js = ArrayHelper::merge($js, $controls);
        $js = ArrayHelper::merge($js, $plugins);
        $js = ArrayHelper::merge($js, $this->_js);
        return $js;
    }

    /**
     * @var PluginManager
     */
    private ?PluginManager $_plugins = null;

    /**
     * @return PluginManager
     */
    public function getPlugins(): PluginManager
    {
        return $this->_plugins;
    }

    /**
     * Installs a plugin
     *
     * @param Plugin $plugin
     */
    public function installPlugin(Plugin $plugin): void
    {
        $plugin->map = $this->name;
        $this->getPlugins()->install($plugin);
    }

    /**
     * Removes an installed plugin
     *
     * @param $plugin
     *
     * @return mixed
     */
    public function removePlugin($plugin): mixed
    {
        return $this->getPlugins()->remove($plugin);
    }

    /**
     * Initializes the widget.
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->center) || empty($this->zoom)) {
            throw new InvalidConfigException("'center' and/or 'zoom' attributes cannot be empty.");
        }
        $this->_plugins = new PluginManager();
        $this->clientOptions['center'] = $this->center->toArray(true);
        $this->clientOptions['zoom'] = $this->zoom;
    }

    /**
     * Helper method to render the widget. It is also possible to use the widget directly:
     * ```
     * echo Map::widget(['leafLet' => $leafLetObject, ...]);
     * ```
     *
     * @param array $config
     *
     * @return string
     */
    public function widget($config = []): string
    {
        ob_start();
        ob_implicit_flush(false);
        $config['leafLet'] = $this;
        $widget = new Map($config);
        $out = $widget->run();
        return ob_get_clean() . $out;
    }

    /**
     * @param Layer $layer the layer script to add to the js script code. It could be any object extending from [[Layer]]
     * component (markers, polylines, popup, etc)
     *
     * @return static the component itself
     */
    public function addLayer(Layer $layer): self
    {
        $this->_layers[] = $layer;
        return $this;
    }

    /**
     * @return Layer[] the stored layers
     */
    public function getLayers(): array
    {
        return $this->_layers;
    }

    /**
     * @param LayerGroup $group sets a layer group
     *
     * @return static the component itself
     */
    public function addLayerGroup(LayerGroup $group): self
    {
        $this->_layerGroups[] = $group;
        return $this;
    }

    /**
     * @return layers\LayerGroup[] all stored layer groups
     */
    public function getLayerGroups(): array
    {
        return $this->_layerGroups;
    }

    /**
     * Clears all stored layer groups
     * @return static the component itself
     */
    public function clearLayerGroups(): self
    {
        $this->_layerGroups = [];
        return $this;
    }

    /**
     * @param Control[] $controls
     *
     * @return array
     */
    public function getEncodedControls($controls): array
    {
        return $this->getEncodedObjects($controls);
    }

    /**
     * @param LayerGroup[] $groups
     *
     * @return array
     */
    public function getEncodedLayerGroups($groups): array
    {
        return $this->getEncodedObjects($groups);
    }

    /**
     * @param Plugin[] $plugins
     *
     * @return array
     */
    public function getEncodedPlugins($plugins): array
    {
        return $this->getEncodedObjects($plugins);
    }

    /**
     * @return string
     */
    public static function generateName(): string
    {
        return self::$autoNamePrefix . self::$counter++;
    }

    /**
     * @param $objects
     *
     * @return array
     */
    protected function getEncodedObjects($objects): array
    {
        $js = [];
        foreach ((array)$objects as $object) {
            if (property_exists($object, 'map')) {
                $object->map = $this->name;
            }
            $js[] = method_exists($object, 'encode') ? $object->encode() : null;
        }
        return array_filter($js);
    }
}
