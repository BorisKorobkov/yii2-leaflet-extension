<?php
declare(strict_types=1);

namespace tests;

use dosamigos\leaflet\layers\Rectangle;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\types\LatLngBounds;

/**
 * @group layers
 */
class RectangleTest extends TestCase
{
    public function testEncode()
    {
        $rectangle = new Rectangle();
        $bounds = new LatLngBounds(
            [
                'southWest' => new LatLng(['lat' => 39.61, 'lng' => -105.02]),
                'northEast' => new LatLng(['lat' => 39.74, 'lng' => -104.88])
            ]
        );
        $rectangle->setBounds($bounds);
        $this->assertEquals($bounds, $rectangle->getBounds());

        $expected = "L.rectangle(L.latLngBounds([39.61,-105.02], [39.74,-104.88]), {});";
        $actual = $rectangle->encode();
        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithName()
    {
        $rectangle = new Rectangle(['name' => 'testRectangle']);
        $bounds = new LatLngBounds(
            [
                'southWest' => new LatLng(['lat' => 39.61, 'lng' => -105.02]),
                'northEast' => new LatLng(['lat' => 39.74, 'lng' => -104.88])
            ]
        );
        $rectangle->setBounds($bounds);
        $this->assertEquals($bounds, $rectangle->getBounds());

        $expected = "var testRectangle = L.rectangle(L.latLngBounds([39.61,-105.02], [39.74,-104.88]), {});";
        $actual = $rectangle->encode();
        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithMapName()
    {
        $rectangle = new Rectangle(['map' => 'testMap']);
        $bounds = new LatLngBounds(
            [
                'southWest' => new LatLng(['lat' => 39.61, 'lng' => -105.02]),
                'northEast' => new LatLng(['lat' => 39.74, 'lng' => -104.88])
            ]
        );
        $rectangle->setBounds($bounds);
        $this->assertEquals($bounds, $rectangle->getBounds());

        $expected = "L.rectangle(L.latLngBounds([39.61,-105.02], [39.74,-104.88]), {}).addTo(testMap);";
        $actual = $rectangle->encode();
        $this->assertEquals($expected, $actual);
    }
}
