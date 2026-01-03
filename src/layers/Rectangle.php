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
use yii\web\JsExpression;

/**
 * Rectangle a class for drawing rectangle overlays on a map.
 *
 * @see https://leafletjs.com/reference.html#rectangle
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package boriskorobkov\leaflet\layers
 */
/**
 * @property string $name
 * @property string $popupContent
 * @property bool $openPopup
 */
class Rectangle extends Layer
{
    use PopupTrait;

    /**
     * @var LatLngBounds
     */
    private ?LatLngBounds $_bounds = null;

    /**
     * @param LatLngBounds $bounds
     */
    public function setBounds(LatLngBounds $bounds): void
    {
        $bounds->name = null; // LatLngBounds has public $name
        $this->_bounds = $bounds;
    }

    /**
     * @return LatLngBounds
     */
    public function getBounds(): ?LatLngBounds
    {
        return $this->_bounds;
    }

    /**
     * Returns the javascript ready code for the object to render
     * @return JsExpression
     */
    public function encode(bool $isAddSemicolon = true): JsExpression
    {
        $bounds = $this->getBounds()->encode();
        $options = $this->getOptions();
        $name = $this->getName();
        $map = $this->map;
        $js = $this->bindPopupContent("L.rectangle($bounds, $options)") . ($map !== null ? ".addTo($map)" : "");
        $js .= $this->getEvents();
        if (!empty($name)) {
            $js = "var $name = $js" . ($isAddSemicolon ? ";" : "");
        } elseif ($isAddSemicolon) {
            $js .= ";";
        }

        return new JsExpression($js);
    }
}
