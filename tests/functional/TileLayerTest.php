<?php
declare(strict_types=1);

namespace tests;

use boriskorobkov\leaflet\layers\TileLayer;

/**
 * @group layers
 */
class TileLayerTest extends TestCase
{
    public function testException()
    {
        $this->expectException('yii\base\InvalidConfigException');
        $tileLayer = new TileLayer();
    }

    public function testEncode()
    {
        $tileLayer = new TileLayer(
            [
                'urlTemplate' => 'https://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg'
            ]
        );
        $expected = 'L.tileLayer("https://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg", {});';
        $actual = $tileLayer->encode();
        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithName() {
        $tileLayer = new TileLayer(
            [
                'name' => 'testTileLayer',
                'urlTemplate' => 'https://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg'
            ]
        );
        $expected = 'var testTileLayer = L.tileLayer("https://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg", {});';
        $actual = $tileLayer->encode();
        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithMap() {
        $tileLayer = new TileLayer(
            [
                'map' => 'map',
                'urlTemplate' => 'https://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg'
            ]
        );
        $expected = 'L.tileLayer("https://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg", {}).addTo(map);';
        $actual = $tileLayer->encode();
        $this->assertEquals($expected, $actual);
    }
}
