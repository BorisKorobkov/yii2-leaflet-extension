<?php
declare(strict_types=1);

namespace tests;

use dosamigos\leaflet\layers\CircleMarker;
use dosamigos\leaflet\types\LatLng;

/**
 * @group layers
 */
class CircleMarkerLayerTest extends TestCase
{
    public function testEncode() {
        $circle = new CircleMarker(['latLng' => new LatLng(['lat' => 50, 'lng' => 50]), 'radius' => 5]);
        $actual = $circle->encode();
        $expected = 'L.circleMarker([50,50], {});';

        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithName() {
        $circle = new CircleMarker(['name' => 'testCircle', 'latLng' => new LatLng(['lat' => 50, 'lng' => 50]), 'radius' => 5]);
        $actual = $circle->encode();
        $expected = 'var testCircle = L.circleMarker([50,50], {});';

        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithMap() {
        $circle = new CircleMarker(['map' => 'testMap', 'latLng' => new LatLng(['lat' => 50, 'lng' => 50]), 'radius' => 5]);
        $actual = $circle->encode();
        $expected = 'L.circleMarker([50,50], {}).addTo(testMap);';

        $this->assertEquals($expected, $actual);
    }
}
