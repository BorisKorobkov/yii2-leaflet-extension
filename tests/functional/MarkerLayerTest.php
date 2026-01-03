<?php
declare(strict_types=1);

namespace tests;


use boriskorobkov\leaflet\layers\Marker;
use boriskorobkov\leaflet\types\Icon;
use boriskorobkov\leaflet\types\LatLng;
use yii\web\JsExpression;

class MarkerLayerTest extends TestCase
{
    public function testInvalidConfiguration()
    {
        $this->expectException('yii\base\InvalidConfigException');
        $marker = new Marker();
    }

    public function testEncode()
    {
        $latLng = new LatLng(['lat' => 51.508, 'lng' => -0.11]);
        $icon = new Icon(['iconUrl' => 'https://example.com/icon.png']);
        $marker = new Marker(
            [
                'icon' => $icon,
                'latLng' => $latLng,
                'popupContent' => 'test!'
            ]
        );

        $this->assertNotNull($marker->icon);
        $expected = 'L.marker([51.508,-0.11], {"icon":L.icon({"iconUrl":"https://example.com/icon.png"})}).bindPopup("test!");';
        $actual = $marker->encode();

        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithName()
    {
        $latLng = new LatLng(['lat' => 51.508, 'lng' => -0.11]);

        $marker = new Marker(
            [
                'name' => 'test',
                'latLng' => $latLng,
                'popupContent' => 'test!',
                'openPopup' => true
            ]
        );

        $expected = 'var test = L.marker([51.508,-0.11], {}).bindPopup("test!").openPopup();';
        $actual = $marker->encode();

        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithNameAndEvents() {
        $latLng = new LatLng(['lat' => 51.508, 'lng' => -0.11]);

        $marker = new Marker(
            [
                'name' => 'test',
                'latLng' => $latLng,
                'map' => 'testMap',
                'clientEvents' => [
                    'click' => new JsExpression('function(e){ console.log(e); }')
                ]
            ]
        );

        $expected = 'var test = L.marker([51.508,-0.11], {}).addTo(testMap).on("click", function(e){ console.log(e); });';
        $actual = $marker->encode();

        $this->assertEquals($expected, $actual);
    }
    
    public function testEncodeWithoutNameAndEvents() {
        $latLng = new LatLng(['lat' => 51.508, 'lng' => -0.11]);

        $marker = new Marker(
            [
                'latLng' => $latLng,
                'map' => 'testMap',
                'clientEvents' => [
                    'click' => new JsExpression('function(e){ console.log(e); }')
                ]
            ]
        );

        $expected = 'L.marker([51.508,-0.11], {}).addTo(testMap).on("click", function(e){ console.log(e); });';
        $actual = $marker->encode();

        $this->assertEquals($expected, $actual);
    }
}
