<?php
declare(strict_types=1);

namespace tests;


use boriskorobkov\leaflet\layers\LayerGroup;
use boriskorobkov\leaflet\layers\Marker;
use boriskorobkov\leaflet\layers\Popup;
use boriskorobkov\leaflet\types\LatLng;
use yii\web\JsExpression;

/**
 * @group layers
 */
class LayerGroupLayerTest extends TestCase
{
    public function testException()
    {
        $this->expectException('yii\base\InvalidArgumentException');
        $layerGroup = new LayerGroup();
        $popup = new Popup(['latLng' => new LatLng(['lat' => 51.508, 'lng' => -0.11])]);
        $layerGroup->addLayer($popup);
    }

    public function testAddRemoveLayer()
    {
        $latLng = new LatLng(['lat' => 51.508, 'lng' => -0.11]);
        $marker = new Marker(
            [
                'name' => 'marker',
                'latLng' => $latLng,
                'map' => 'testMap',
                'clientEvents' => [
                    'click' => new JsExpression('function(e){ console.log(e); }')
                ]
            ]
        );

        $layerGroup = new LayerGroup();
        $layerGroup->addLayer($marker);

        $this->assertNotNull($layerGroup->getLayer('marker'));
        $this->assertNotNull($layerGroup->removeLayer('marker'));
        $this->assertCount(0, $layerGroup->getLayers());
    }

    public function testEncode()
    {
        $littleton = new Marker(['latLng' => new LatLng(['lat' => 39.61, 'lng' => -105.02])]);
        $denver = new Marker(['latLng' => new LatLng(['lat' => 39.74, 'lng' => -104.99])]);

        $group = new LayerGroup();
        $group->addLayer($littleton);
        $group->addLayer($denver);

        $actual = $group->encode();
        $expected = file_get_contents(__DIR__ . '/data/layerGroup-layer.js');
        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithName()
    {
        $littleton = new Marker(['latLng' => new LatLng(['lat' => 39.61, 'lng' => -105.02])]);
        $denver = new Marker(['latLng' => new LatLng(['lat' => 39.74, 'lng' => -104.99])]);

        $group = new LayerGroup(['name' => 'group']);
        $group->addLayer($littleton);
        $group->addLayer($denver);

        $actual = $group->encode();
        $expected = file_get_contents(__DIR__ . '/data/layerGroup-layer-with-name.js');
        $this->assertEquals($expected, $actual);
    }

    public function testOneLineEncode()
    {
        $littleton = new Marker(['latLng' => new LatLng(['lat' => 39.61, 'lng' => -105.02])]);
        $denver = new Marker(['latLng' => new LatLng(['lat' => 39.74, 'lng' => -104.99])]);

        $group = new LayerGroup(['map' => 'testMap']);
        $group->addLayer($littleton);
        $group->addLayer($denver);

        $actual = $group->oneLineEncode();
        $expected = 'L.layerGroup([L.marker([39.61,-105.02], {}),L.marker([39.74,-104.99], {})]).addTo(testMap);';
        $this->assertEquals($expected, $actual);
    }
}
