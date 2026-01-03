<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */
/**
 * @copyright Copyright (c) 2026 Boris Korobkov
 * @link https://github.com/BorisKorobkov
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace boriskorobkov\leaflet\layers;

use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * LayerGroup Used to group several layers and handle them as one.
 *
 * @see https://leafletjs.com/reference.html#layergroup
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package boriskorobkov\leaflet\layers
 */
class LayerGroup extends Component
{
    use NameTrait;

    /**
     * @var string the name of the javascript variable that will hold the reference
     * to the map object.
     */
    public ?string $map = null;

    /**
     * @var Layer[]
     */
    private array $_layers = [];

    /**
     * Adds a layer to the group. If no name given it will be automatically generated.
     *
     * @param Layer $layer
     *
     * @return $this
     * @throws \yii\base\InvalidArgumentException
     */
    public function addLayer(Layer $layer): self
    {
        if (($layer instanceof Popup) || ($layer instanceof TileLayer)) {
            throw new InvalidArgumentException("'\$layer' cannot be of type Popup or TileLayer.");
        }
        $layer->map = null;
        $this->_layers[$layer->getName(true)] = $layer;
        return $this;
    }

    /**
     * Returns a specific layer. Please note that if the layer didn't have a name, it will be dynamically created. This
     * method works for those that we know the name previously.
     *
     * @param string $name the name of the layer
     *
     * @return mixed
     */
    public function getLayer(string $name): mixed
    {
        return ArrayHelper::getValue($this->_layers, $name);
    }

    /**
     * Removes a layer with the given name from the group.
     *
     * @param $name
     *
     * @return mixed|null
     */
    public function removeLayer(string $name): mixed
    {
        return ArrayHelper::remove($this->_layers, $name);
    }

    /**
     * @return Layer[] the added layers
     */
    public function getLayers(): array
    {
        return $this->_layers;
    }

    /**
     * @return JsExpression
     */
    public function encode(bool $isAddSemicolon = true): JsExpression
    {
        $js = [];
        $layers = $this->getLayers();
        $name = $this->getName();
        $names = str_replace(['"', "'"], "", Json::encode(array_keys($layers)));
        $map = $this->map;
        foreach ($layers as $layer) {
            $js[] = $layer->encode();
        }
        $initJs = "L.layerGroup($names)" . ($map !== null ? ".addTo($map)" : "");

        if (!empty($name)) {
            $js[] = "var $name = $initJs" . ($isAddSemicolon ? ";" : "");
        } else {
            $js[] = $initJs . ($isAddSemicolon ? ";" : "");
        }
        return new JsExpression(implode("\n", $js));
    }

    /**
     * Returns the initialization
     * @return JsExpression
     */
    public function oneLineEncode(): JsExpression
    {
        $map = $this->map;
        $layers = $this->getLayers();
        $layersJs = [];
        /** @var \boriskorobkov\leaflet\layers\Layer $layer */
        foreach ($layers as $layer) {
            $layer->setName(null);
            $layersJs[] = $layer->encode(false);
        }
        $js = "L.layerGroup([" . implode(",", $layersJs) . "])" . ($map !== null ? ".addTo($map);" : "");
        return new JsExpression($js);
    }
}
