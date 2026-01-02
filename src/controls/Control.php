<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\leaflet\controls;

use dosamigos\leaflet\LeafLet;
use yii\base\Component;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Control is the base class for all Controls
 *
 * @property string $name
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package dosamigos\leaflet\controls
 */
abstract class Control extends Component
{
    /**
     * @var string the name of the javascript variable that will hold the reference
     * to the map object.
     */

    public ?string $map = null;
    /**
     * @var string the initial position of the control (one of the map corners).
     */
    public string $position = 'topright';

    /**
     * @var array the options for the underlying LeafLetJs JS component.
     * Please refer to the LeafLetJs api reference for possible
     * [options](https://leafletjs.com/reference.html).
     */
    public array $clientOptions = [];
 
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
     * @return string
     */
    public function getOptions(): string
    {
        return empty($this->clientOptions) ? '{}' : Json::encode($this->clientOptions, LeafLet::JSON_OPTIONS);
    }

    /**
     * Returns the javascript ready code for the object to render
     * @return \yii\web\JsExpression
     */
    abstract public function encode(): JsExpression;
}
