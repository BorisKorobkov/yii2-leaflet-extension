<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\leaflet;

use yii\base\Component;
use yii\helpers\Json;
use yii\web\View;

/**
 * @property string $name
 */

/**
 * Plugin is the abstract class where all plugins should extend from
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package dosamigos\leaflet
 */
abstract class Plugin extends Component
{
    /**
     * @var string the map name
     */
    public ?string $map = null;
    
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
     * @var string the variable name. If not null, then the js creation script
     * will be returned as a variable. If null, then the js creation script will
     * be returned as a constructor that you can use on other object's configuration options.
     */
    private ?string $_name = null;

    /**
     * Returns the name of the layer.
     *
     * @param boolean $autoGenerate whether to generate a name if it is not set previously
     *
     * @return string name of the layer.
     */
    public function getName(bool $autoGenerate = false): ?string
    {
        if ($autoGenerate && $this->_name === null) {
            $this->_name = LeafLet::generateName();
        }
        return $this->_name;
    }

    /**
     * Sets the name of the layer.
     *
     * @param string $value name of the layer.
     */
    public function setName(string $value): void
    {
        $this->_name = $value;
    }

    /**
     * Returns the processed js options
     * @return array
     */
    public function getOptions(): string
    {
        return empty($this->clientOptions) ? '{}' : Json::encode($this->clientOptions, LeafLet::JSON_OPTIONS);
    }

    /**
     * @return string the processed js events
     */
    public function getEvents(): string
    {
        $js = [];
        $name = $this->getName();
        if (!empty($name) && !empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "$name.on('$event', $handler);";
            }
        }
        return !empty($js) ? implode("\n", $js) : "";
    }

    /**
     * Returns the plugin name
     * @return string
     */
    abstract public function getPluginName(): string;

    /**
     * Registers plugin asset bundle
     *
     * @param View $view
     *
     * @return void
     */
    abstract public function registerAssetBundle(View $view): void;

    /**
     * Returns the javascript ready code for the object to render
     * @return string
     */
    abstract public function encode(): string;
}
