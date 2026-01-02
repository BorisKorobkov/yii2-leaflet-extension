<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\leaflet\layers;

use dosamigos\leaflet\LeafLet;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * GeoJson allows you to parse GeoJSON data and display it on the map
 *
 * @see https://leafletjs.com/reference.html#geojson
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package dosamigos\leaflet\layers
 */
/**
 * @property string $name
 */
class GeoJson extends Layer
{
    /**
     * @var array geo spatial data interchange json object. For information related to GeoJSON format, please visit
     * [http://geojson.org/geojson-spec.html](http://geojson.org/geojson-spec.html). This component does not validate
     * this data, it just renders it. This array will be converted into a json object previous encoding.
     */
    public array $data = [];

    /**
     * Returns the javascript ready code for the object to render
     * @return JsExpression
     */
    public function encode(): JsExpression
    {
        $data = Json::encode($this->data, LeafLet::JSON_OPTIONS);
        $options = $this->getOptions();
        $name = $this->getName();
        $map = $this->map;
        $js = "L.geoJson($data, $options)" . ($map !== null ? ".addTo($map)" : "");
        if (!empty($name)) {
            $js = "var $name = $js;";
        }
        $js .= $this->getEvents() . ($map !== null && empty($name) ? ";" : "");

        return new JsExpression($js);
    }
}
