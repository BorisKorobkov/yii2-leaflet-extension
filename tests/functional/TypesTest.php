<?php
declare(strict_types=1);

namespace tests;

use dosamigos\leaflet\types\Bounds;
use dosamigos\leaflet\types\DivIcon;
use dosamigos\leaflet\types\Icon;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\types\LatLngBounds;
use dosamigos\leaflet\types\Point;

/**
 * @group types
 */
class TypesTest extends TestCase
{
    public function testLatLngException()
    {
        $this->expectException('yii\base\InvalidConfigException');
        $latLng = new LatLng();
    }

    public function testPoint()
    {
        $point = new Point(['x' => 1, 'y' => 2]);
        $this->assertEquals('L.point(1, 2)', $point->encode());
        $this->assertEquals([1, 2], $point->toArray());

        $this->expectException('yii\base\InvalidConfigException');
        $point = new Point();

    }

    public function testBounds()
    {
        $pointMax = new Point(['x' => 1, 'y' => 2]);
        $pointMin = new Point(['x' => 0.5, 'y' => 1]);

        $bounds = new Bounds(['min' => $pointMin, 'max' => $pointMax]);
        $this->assertEquals($pointMax, $bounds->getMax());
        $this->assertEquals($pointMin, $bounds->getMin());

        $this->assertEquals('L.bounds([0.5,1], [1,2])', $bounds->encode());

        $this->assertEquals([[0.5, 1], [1, 2]], $bounds->toArray());

        $this->expectException('yii\base\InvalidConfigException');
        $bounds = new Bounds();
    }

    public function testLatLngBounds()
    {
        $bounds = new LatLngBounds(
            [
                'name' => 'testName',
                'southWest' => new LatLng(['lat' => 39.61, 'lng' => -105.02]),
                'northEast' => new LatLng(['lat' => 39.74, 'lng' => -104.88])
            ]
        );
        $actual = $bounds->encode();
        $expected = 'var testName = L.latLngBounds([39.61,-105.02], [39.74,-104.88]);';
        $this->assertEquals($expected, $actual);
        $this->expectException('yii\base\InvalidConfigException');
        $bounds = new LatLngBounds();
    }

    public function testGetBoundsOfLatLngs()
    {

        $expected = new LatLngBounds(
            [
                'southWest' => new LatLng(['lat' => 26.61, 'lng' => -119.02]),
                'northEast' => new LatLng(['lat' => 52.74, 'lng' => -90.88])
            ]
        );
        $actual = LatLngBounds::getBoundsOfLatLngs(
            [
                new LatLng(['lat' => 39.61, 'lng' => -105.02]),
                new LatLng(['lat' => 39.74, 'lng' => -104.88])
            ],
            100
        );
        $this->assertEquals($expected, $actual);

        $this->expectException('yii\base\InvalidArgumentException');
        LatLngBounds::getBoundsOfLatLngs(['wrong']);
    }

    public function testIcon()
    {
        $point = new Point(['x' => 1, 'y' => 2]);

        $icon = new Icon(['iconUrl' => 'http://example.com/img.png']);

        $icon->setIconSize($point);
        $this->assertEquals($point, $icon->getIconSize());
        $icon->setIconAnchor($point);
        $this->assertEquals($point, $icon->getIconAnchor());
        $icon->setPopupAnchor($point);
        $this->assertEquals($point, $icon->getPopupAnchor());
        $icon->setShadowAnchor($point);
        $this->assertEquals($point, $icon->getShadowAnchor());
        $icon->setShadowSize($point);
        $this->assertEquals($point, $icon->getShadowSize());

        $icon->name = 'testIcon';

        $expected = 'var testIcon = L.icon({"name":"testIcon","iconUrl":"http://example.com/img.png","iconAnchor":[1,2],"iconSize":[1,2],"popupAnchor":[1,2],"shadowAnchor":[1,2],"shadowSize":[1,2]});';
        $actual = $icon->encode();
        $this->assertEquals($expected, $actual);

        $this->expectException('yii\base\InvalidConfigException');
        $icon = new Icon();
    }

    public function testDivIcon()
    {
        $point = new Point(['x' => 1, 'y' => 2]);

        $icon = new DivIcon();

        $icon->setIconSize($point);
        $this->assertEquals($point, $icon->getIconSize());
        $icon->setIconAnchor($point);
        $icon->className = 'my-div-icon';
        $icon->html = '<div/>';
        $icon->name = 'testName';

        $expected = 'var testName = L.divIcon({"name":"testName","className":"my-div-icon","html":"<div/>","iconSize":[1,2],"iconAnchor":[1,2]});';
        $actual = $icon->encode();
        $this->assertEquals($expected, $actual);
    }
}
