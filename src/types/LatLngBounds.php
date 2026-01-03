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
namespace boriskorobkov\leaflet\types;

use yii\base\InvalidConfigException;
use yii\base\InvalidArgumentException;
use yii\web\JsExpression;

/**
 * LatLngBounds represents a rectangular geographical area on a map.
 *
 * @see https://leafletjs.com/reference.html#latlngbounds
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package boriskorobkov\leaflet\types
 */

/**
 * @property LatLng $southWest
 * @property LatLng $northEast
 */
class LatLngBounds extends Type
{
    /**
     * @var string the variable name. If not null, then the js icon creation script
     * will be returned as a variable:
     *
     * ```
     * var bounds = L.latLngBounds(...);
     * // after it can be included to the map
     * map.fitBounds(bounds);
     * ```
     * If null, the js icon creation script will be returned to be used as constructor so it can be used within another
     * constructor options:
     *
     * ```
     * L.map({maxBounds: L.latLngBounds(...));
     * ```
     */
    public ?string $name = null;

    /**
     * @var LatLng the southWest boundary
     */
    private ?LatLng $_southWest = null;
    
    /**
     * @var LatLng the northEast boundary
     */
    private ?LatLng $_northEast = null;

    /**
     * @return LatLng
     */
    public function getSouthWest(): ?LatLng
    {
        return $this->_southWest;
    }

    /**
     * @param LatLng $latLng
     */
    public function setSouthWest(LatLng $latLng): void
    {
        $this->_southWest = $latLng;
    }

    /**
     * @return LatLng
     */
    public function getNorthEast(): ?LatLng
    {
        return $this->_northEast;
    }

    /**
     * @param LatLng $latLng
     */
    public function setNorthEast(LatLng $latLng): void
    {
        $this->_northEast = $latLng;
    }

    /**
     * Initializes the class
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->southWest) || empty($this->northEast)) {
            throw new InvalidConfigException("'southEast' and/or 'northEast' cannot be empty");
        }
    }

    /**
     * @return \yii\web\JsExpression the js initialization code of the object
     */
    public function encode(): JsExpression
    {
        $southWest = $this->getSouthWest()->toArray(true);
        $northEast = $this->getNorthEast()->toArray(true);
        $js = "L.latLngBounds($southWest, $northEast)";
        if (!empty($this->name)) {
            $js = "var $this->name = $js;";
        }
        return new JsExpression($js);
    }

    /**
     * Finds bounds of an array of LatLng instances
     *
     * @param LatLng[] $latLngs
     * @param int $margin
     *
     * @return LatLngBounds
     */
    public static function getBoundsOfLatLngs(array $latLngs, $margin = 0): LatLngBounds
    {
        $min_lat = 1000;
        $max_lat = -1000;
        $min_lng = 1000;
        $max_lng = -1000;
        foreach ($latLngs as $latLng) {
            if (!($latLng instanceof LatLng)) {
                throw new InvalidArgumentException('"$latLngs" should be an array of LatLng instances.');
            }
            /* @var $coord LatLng */
            $min_lat = min($min_lat, $latLng->lat);
            $max_lat = max($max_lat, $latLng->lat);
            $min_lng = min($min_lng, $latLng->lng);
            $max_lng = max($max_lng, $latLng->lng);
        }
        if ($margin > 0) {
            $latDiff = $max_lat - $min_lat;
            $lngDiff = $max_lng - $min_lng;

            $min_lat -= $margin * $latDiff;
            $min_lng -= $margin * $lngDiff;
            $max_lat += $margin * $latDiff;
            $max_lng += $margin * $lngDiff;
        }
        $bounds = new LatLngBounds(
            [
                'southWest' => new LatLng(['lat' => round($min_lat, 2), 'lng' => round($min_lng, 2)]),
                'northEast' => new LatLng(['lat' => round($max_lat, 2), 'lng' => round($max_lng, 2)])
            ]
        );
        return $bounds;
    }
}
