<?php
declare(strict_types=1);

namespace tests;


use boriskorobkov\leaflet\layers\GeoJson;

/**
 * @group layers
 */
class GeoJsonLayerTest extends TestCase
{
    public function testEncode()
    {
        $json = new GeoJson(
            [
                'data' => [
                    "type" => "MultiPoint",
                    "coordinates" => [[100.0, 0.0], [101.0, 1.0]]
                ]
            ]
        );

        $expected = 'L.geoJson({"type":"MultiPoint","coordinates":[[100,0],[101,1]]}, {});';
        $actual = $json->encode();

        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithName() {
        $json = new GeoJson(
            [
                'name' => 'testJson',
                'data' => [
                    "type" => "MultiPoint",
                    "coordinates" => [[100.0, 0.0], [101.0, 1.0]]
                ]
            ]
        );

        $expected = 'var testJson = L.geoJson({"type":"MultiPoint","coordinates":[[100,0],[101,1]]}, {});';
        $actual = $json->encode();

        $this->assertEquals($expected, $actual);
    }
}
