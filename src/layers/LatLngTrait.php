<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\leaflet\layers;

use dosamigos\leaflet\types\LatLng;

trait LatLngTrait
{
    /**
     * @var \dosamigos\leaflet\types\LatLng holds the latitude and longitude values.
     */
    private ?LatLng $_latLon = null;

    /**
     * @param \dosamigos\leaflet\types\LatLng $latLon the position to render the marker
     */
    public function setLatLng(LatLng $latLon): void
    {
        $this->_latLon = $latLon;
    }

    /**
     * @return \dosamigos\leaflet\types\LatLng
     */
    public function getLatLng(): ?LatLng
    {
        return $this->_latLon;
    }
}
