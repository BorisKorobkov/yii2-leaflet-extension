<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\leaflet\layers;

use dosamigos\leaflet\LeafLet;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\types\LatLngBounds;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * PolyLine is a class for drawing a polygon overlay on the map.
 *
 * @see https://leafletjs.com/reference.html#polyline
 * @package dosamigos\leaflet\layers
 */
/**
 * @property string $name
 * @property string $popupContent
 * @property bool $openPopup
 */
class PolyLine extends Layer
{
    use PopupTrait;

    /**
     * @var LatLng[]
     */
    private array $_latLngs = [];

    /**
     * @var LatLngBounds
     */
    private ?LatLngBounds $_bounds = null;

    /**
     * @param array $latLngs
     *
     * @throws \yii\base\InvalidArgumentException
     */
    public function setLatLngs(array $latLngs): void
    {
        foreach ($latLngs as $latLng) {
            if (!($latLng instanceof LatLng)) {
                throw new InvalidArgumentException("Wrong parameter. All items should be of type LatLng.");
            }
        }
        $this->_latLngs = $latLngs;
        $this->setBounds();
    }

    /**
     * @return \dosamigos\leaflet\types\LatLng[]
     */
    public function getLatLngs(): array
    {
        return $this->_latLngs;
    }

    /**
     * Returns the latLngs as array objects
     * @return array
     */
    public function getLatLngstoArray(): array
    {
        $latLngs = [];
        foreach ($this->getLatLngs() as $latLng) {
            $latLngs[] = $latLng->toArray();
        }
        return $latLngs;
    }

    /**
     * Returns the LatLngBounds of the polyline.
     * @return LatLngBounds
     */
    public function getBounds(): ?LatLngBounds
    {
        return $this->_bounds;
    }

    /**
     * Sets bounds after initialization of the [[LatLng]] objects that compound the polyline.
     */
    protected function setBounds(): void
    {
        $this->_bounds = LatLngBounds::getBoundsOfLatLngs($this->getLatLngs());
    }

    /**
     * Returns the javascript ready code for the object to render
     * @return JsExpression
     */
    public function encode(): JsExpression
    {
        $latLngs = Json::encode($this->getLatLngstoArray(), LeafLet::JSON_OPTIONS);
        $options = $this->getOptions();
        $name = $this->getName();
        $map = $this->map;
        $js = $this->bindPopupContent("L.polyline($latLngs, $options)") . ($map !== null ? ".addTo($map)" : "");
        if (!empty($name)) {
            $js = "var $name = $js;";
        }

        $js .= $this->getEvents() . ($map !== null && empty($name) ? ";" : "");
        return new JsExpression($js);
    }

}
