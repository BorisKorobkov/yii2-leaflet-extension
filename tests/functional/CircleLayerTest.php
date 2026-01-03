<?php
declare(strict_types=1);

namespace tests;


use dosamigos\leaflet\layers\Circle;
use dosamigos\leaflet\types\LatLng;

/**
 * @group layers
 */
class CircleLayerTest extends TestCase
{
    public function testEncode() {
        $circle = new Circle(['latLng' => new LatLng(['lat' => 50, 'lng' => 50]), 'radius' => 5]);
        $actual = $circle->encode();
        $expected = 'L.circle([50,50], 5, {});';

        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithName() {
        $circle = new Circle(['name' => 'testCircle', 'latLng' => new LatLng(['lat' => 50, 'lng' => 50]), 'radius' => 5]);
        $actual = $circle->encode();
        $expected = 'var testCircle = L.circle([50,50], 5, {});';

        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithMap() {
        $circle = new Circle(['map' => 'testMap', 'latLng' => new LatLng(['lat' => 50, 'lng' => 50]), 'radius' => 5]);
        $actual = $circle->encode();
        $expected = 'L.circle([50,50], 5, {}).addTo(testMap);';

        $this->assertEquals($expected, $actual);
    }
}
