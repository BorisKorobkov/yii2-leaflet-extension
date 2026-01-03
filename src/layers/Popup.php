<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\leaflet\layers;

use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Popup is used to open popups in certain places of the map. For popups directly attached to an
 * object (ie [[Marker]]) better use their `popup` attribute.
 *
 * @see https://leafletjs.com/reference.html#popup
 * @package dosamigos\leaflet\layers
 */

/**
 * @property \dosamigos\leaflet\types\LatLng $latLng
 */
class Popup extends Layer
{
    use LatLngTrait;

    /**
     * @var string the HTML content of the popup
     */
    public ?string $content = null;

    /**
     * Initializes the marker.
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        if ($this->getLatLng() === null) {
            throw new InvalidConfigException("'latLon' attribute cannot be empty.");
        }
    }

    /**
     * Returns the javascript ready code for the object to render
     * @return JsExpression
     */
    public function encode(bool $isAddSemicolon = true): JsExpression
    {
        $latLon = $this->getLatLng()->encode();
        $options = $this->getOptions();
        $name = $this->getName();
        $map = $this->map;
        $js = "L.popup($options).setLatLng($latLon).setContent(" . Json::encode($this->content) . ")" . ($map !== null ? ".addTo($map)" : "");
        $js .= $this->getEvents();
        if (!empty($name)) {
            $js = "var $name = $js" . ($isAddSemicolon ? ";" : "");
        } elseif ($isAddSemicolon) {
            $js .= ";";
        }

        return new JsExpression($js);
    }
}
