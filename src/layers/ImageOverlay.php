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

use boriskorobkov\leaflet\types\LatLngBounds;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * ImageOverlay it is used to load and display a single image over specific bounds of the map
 *
 * @see https://leafletjs.com/reference.html#imageoverlay
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package extensions\leafletjs\layers
 */
/**
 * @property string $name
 */
class ImageOverlay extends Layer
{
    /**
     * @var string the image Url
     */
    public ?string $imageUrl = null;

    /**
     * @var LatLngBounds
     */
    private ?LatLngBounds $_bounds = null;

    /**
     * @param LatLngBounds $bounds
     */
    public function setImageBounds(LatLngBounds $bounds): void
    {
        $this->_bounds = $bounds;
    }

    /**
     * @return LatLngBounds
     */
    public function getImageBounds(): ?LatLngBounds
    {
        return $this->_bounds;
    }

    /**
     * Returns the javascript ready code for the object to render
     * @return \yii\web\JsExpression
     */
    public function encode(bool $isAddSemicolon = true): JsExpression
    {
        $name = $this->getName();
        $imageUrl = $this->imageUrl;
        $bounds = $this->getImageBounds()->encode();
        $options = $this->getOptions();
        $map = $this->map;
        $js = "L.imageOverlay(" . Json::encode($imageUrl) . ", $bounds, $options)" . ($map !== null ? ".addTo($map)" : "");
        $js .= $this->getEvents();
        if (!empty($name)) {
            $js = "var $name = $js" . ($isAddSemicolon ? ";" : "");
        } elseif ($isAddSemicolon) {
            $js .= ";";
        }

        return new JsExpression($js);
    }
}
