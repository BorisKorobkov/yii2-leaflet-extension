<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\leaflet\types;

use dosamigos\leaflet\LeafLet;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * LatLng
 * Represents a geographical point with a certain latitude and longitude. Please, note that
 * all Leaflet methods that accept LatLng objects also accept them in a simple Array form and simple object form
 * (unless noted otherwise), so these lines are equivalent:
 *
 * ```
 * use dosamigos\leafletjs\layers\Marker;
 * use dosamigos\leafletjs\types\LatLng;
 *
 * $marker = new Marker(['latLong'=>[50, 30]]);
 * $marker = new Marker(new LatLng(['latLng'=>[50,30]]));
 * ```
 *
 * @see http://leafletjs.com/reference.html#latlng
 * @see http://leafletjs.com/reference.html#bounds
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package dosamigos\leaflet\types
 */
class LatLng extends Type implements ArrayableInterface
{
    /**
     * @var float the latitude in degrees.
     */
    public float $lat;
    
    /**
     * @var float the longitude in degrees.
     */
    public float $lng;

    /**
     * Initializes the object
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        if (!isset($this->lat) || !isset($this->lng)) {
            throw new InvalidConfigException("'lat' and 'lng' attributes cannot be empty.");
        }
    }

    /**
     * LatLng is and object to be used
     * @return \yii\web\JsExpression the js initialization code of the object
     */
    public function encode(): JsExpression
    {
        return new JsExpression("L.latLng($this->lat, $this->lng)"); // no semicolon
    }

    /**
     * Returns the lat and lng as array
     *
     * @param bool $encode whether to return the array json_encoded or raw
     *
     * @return array|JsExpression
     */
    public function toArray($encode = false): mixed
    {
        $latLng = [$this->lat, $this->lng];

        return $encode
            ? new JsExpression(Json::encode($latLng, LeafLet::JSON_OPTIONS))
            : $latLng;
    }
}
