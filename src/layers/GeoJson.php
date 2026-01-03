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
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * GeoJson allows you to parse GeoJSON data and display it on the map
 *
 * @see https://leafletjs.com/reference.html#geojson
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package boriskorobkov\leaflet\layers
 */
/**
 * @property string $name
 */
class GeoJson extends Layer
{
    /**
     * @var array geo spatial data interchange json object. For information related to GeoJSON format, please visit
     * [https://geojson.org/geojson-spec.html](https://geojson.org/geojson-spec.html). This component does not validate
     * this data, it just renders it. This array will be converted into a json object previous encoding.
     */
    public array $data = [];

    /**
     * Returns the javascript ready code for the object to render
     * @return JsExpression
     */
    public function encode(bool $isAddSemicolon = true): JsExpression
    {
        $data = Json::encode($this->data, LeafLet::JSON_OPTIONS);
        $options = $this->getOptions();
        $name = $this->getName();
        $map = $this->map;
        $js = "L.geoJson($data, $options)" . ($map !== null ? ".addTo($map)" : "");
        $js .= $this->getEvents();
        if (!empty($name)) {
            $js = "var $name = $js" . ($isAddSemicolon ? ";" : "");
        } elseif ($isAddSemicolon) {
            $js .= ";";
        }

        return new JsExpression($js);
    }
}
