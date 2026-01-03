<?php
declare(strict_types=1);

namespace tests;

use dosamigos\leaflet\layers\Popup;
use dosamigos\leaflet\types\LatLng;

/**
 * @group layers
 */
class PopupTest extends TestCase
{
    public function testEncode()
    {
        $popup = new Popup(
            [
                'latLng' => new LatLng(['lat' => 39.61, 'lng' => -105.02]),
                'content' => 'Hey!'
            ]
        );
        $expected1 = 'L.popup({}).setLatLng(L.latLng(39.61, -105.02)).setContent("Hey!");';
        $actual1 = $popup->encode();
        $this->assertEquals($expected1, $actual1);

        $popup->name = 'testName';
        $expected2 = 'var testName = L.popup({}).setLatLng(L.latLng(39.61, -105.02)).setContent("Hey!");';
        $actual2 = $popup->encode();
        $this->assertEquals($expected2, $actual2);

        $popup->map = 'testMap';
        $expected3 = 'var testName = L.popup({}).setLatLng(L.latLng(39.61, -105.02)).setContent("Hey!").addTo(testMap);';
        $actual3 = $popup->encode();
        $this->assertEquals($expected3, $actual3);
    }

    public function testException()
    {
        $this->expectException('yii\base\InvalidConfigException');
        new Popup();
    }
}
