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

use yii\web\JsExpression;

/**
 * CircleMarker is a circle of a fixed size with radius specified in pixels. Setting its radius wont change its size.
 *
 * @see https://leafletjs.com/reference.html#circlemarker
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package boriskorobkov\leaflet\layers
 */
/**
 * @property string $name
 */
class CircleMarker extends Circle
{
    /**
     * Returns the javascript ready code for the object to render
     * @return JsExpression
     */
    public function encode(bool $isAddSemicolon = true): JsExpression
    {
        $bounds = $this->getLatLng()->toArray(true);
        $options = $this->getOptions();
        $name = $this->getName();
        $map = $this->map;
        $js = $this->bindPopupContent("L.circleMarker($bounds, $options)") . ($map !== null ? ".addTo($map)" : "");
        $js .= $this->getEvents();
        if (!empty($name)) {
            $js = "var $name = $js" . ($isAddSemicolon ? ";" : "");
        } elseif ($isAddSemicolon) {
            $js .= ";";
        }

        return new JsExpression($js);
    }
}
