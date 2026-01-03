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

use boriskorobkov\leaflet\LeafLet;
use boriskorobkov\leaflet\types\Type;
use yii\base\Component;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Layer is the base class for UI Layers
 *
 * @property string $name
 *
 * @package boriskorobkov\leaflet\layers
 */
abstract class Layer extends Component
{
    use NameTrait;

    /**
     * @var string the name of the javascript variable that will hold the reference
     * to the map object.
     */
    public ?string $map = null;

    /**
     * @var array the options for the underlying LeafLetJs JS component.
     * Please refer to the LeafLetJs api reference for possible
     * [options](https://leafletjs.com/reference.html).
     */
    public array $clientOptions = [];

    /**
     * @var array the event handlers for the underlying LeafletJs JS plugin.
     * Please refer to the LeafLetJs js api object options for possible events.
     */
    public array $clientEvents = [];

    /**
     * Returns the processed js options
     * @return string
     */
    public function getOptions(): string
    {
        $options = [];
        foreach ($this->clientOptions as $key => $option) {
            if ($option instanceof Type) {
                $option = $option->encode();
            }
            $options[$key] = $option;
        }
        return empty($options) ? '{}' : Json::encode($options, LeafLet::JSON_OPTIONS);
    }

    /**
     * @return string the processed js events
     */
    public function getEvents(): string
    {
        $js = [];
        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = ".on(" . Json::encode($event) . ", $handler)";
            }
        }

        return implode("", $js);
    }

    /**
     * Returns the javascript ready code for the object to render
     * @return \yii\web\JsExpression
     */
    abstract public function encode(bool $isAddSemicolon = true): JsExpression;
}
