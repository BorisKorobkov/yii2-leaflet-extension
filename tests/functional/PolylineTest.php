<?php
declare(strict_types=1);

namespace tests;

use dosamigos\leaflet\layers\PolyLine;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\types\LatLngBounds;

/**
 * @group layers
 */
class PolylineTest extends TestCase
{
    public function testEncode()
    {
        $latLngs = [
            new LatLng(['lat' => 39.61, 'lng' => -105.02]),
            new LatLng(['lat' => 39.73, 'lng' => -104.88]),
            new LatLng(['lat' => 39.74, 'lng' => -104.99])
        ];
        $polyline = new PolyLine();
        $polyline->setLatLngs($latLngs);

        $this->assertCount(3, $polyline->getLatLngs());

        $expected1 = [
            [39.61, -105.02],
            [39.73, -104.88],
            [39.74, -104.99]
        ];
        $actual1 = $polyline->getLatLngstoArray();
        $this->assertEquals($expected1, $actual1);

        $expected2 = new LatLngBounds(
            [
                'southWest' => new LatLng(['lat' => 39.61, 'lng' => -105.02]),
                'northEast' => new LatLng(['lat' => 39.74, 'lng' => -104.88])
            ]
        );
        $actual2 = $polyline->getBounds();
        $this->assertEquals($expected2, $actual2);

        $expected3 = "L.polyline([[39.61,-105.02],[39.73,-104.88],[39.74,-104.99]], {});";
        $actual3 = $polyline->encode();
        $this->assertEquals($expected3, $actual3);
    }

    public function testEncodeWithName()
    {
        $latLngs = [
            new LatLng(['lat' => 39.61, 'lng' => -105.02]),
            new LatLng(['lat' => 39.73, 'lng' => -104.88]),
            new LatLng(['lat' => 39.74, 'lng' => -104.99])
        ];
        $polyline = new PolyLine(['name' => 'testPolyline']);
        $polyline->setLatLngs($latLngs);

        $this->assertCount(3, $polyline->getLatLngs());

        $expected1 = [
            [39.61, -105.02],
            [39.73, -104.88],
            [39.74, -104.99]
        ];
        $actual1 = $polyline->getLatLngstoArray();
        $this->assertEquals($expected1, $actual1);

        $expected2 = new LatLngBounds(
            [
                'southWest' => new LatLng(['lat' => 39.61, 'lng' => -105.02]),
                'northEast' => new LatLng(['lat' => 39.74, 'lng' => -104.88])
            ]
        );
        $actual2 = $polyline->getBounds();
        $this->assertEquals($expected2, $actual2);

        $expected3 = "var testPolyline = L.polyline([[39.61,-105.02],[39.73,-104.88],[39.74,-104.99]], {});";
        $actual3 = $polyline->encode();
        $this->assertEquals($expected3, $actual3);
    }

    public function testEncodeWithMapName()
    {
        $latLngs = [
            new LatLng(['lat' => 39.61, 'lng' => -105.02]),
            new LatLng(['lat' => 39.73, 'lng' => -104.88]),
            new LatLng(['lat' => 39.74, 'lng' => -104.99])
        ];
        $polyline = new PolyLine(['map' => 'testMap']);
        $polyline->setLatLngs($latLngs);

        $this->assertCount(3, $polyline->getLatLngs());

        $expected1 = [
            [39.61, -105.02],
            [39.73, -104.88],
            [39.74, -104.99]
        ];
        $actual1 = $polyline->getLatLngstoArray();
        $this->assertEquals($expected1, $actual1);

        $expected2 = new LatLngBounds(
            [
                'southWest' => new LatLng(['lat' => 39.61, 'lng' => -105.02]),
                'northEast' => new LatLng(['lat' => 39.74, 'lng' => -104.88])
            ]
        );
        $actual2 = $polyline->getBounds();
        $this->assertEquals($expected2, $actual2);

        $expected3 = "L.polyline([[39.61,-105.02],[39.73,-104.88],[39.74,-104.99]], {}).addTo(testMap);";
        $actual3 = $polyline->encode();
        $this->assertEquals($expected3, $actual3);
    }

    public function testException() {
        $polyline = new PolyLine(['map' => 'testMap']);

        $this->expectException('yii\base\InvalidArgumentException');
        $polyline->setLatLngs(['wrongValue']);
    }
}
