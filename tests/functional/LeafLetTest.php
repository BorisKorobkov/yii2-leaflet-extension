<?php
declare(strict_types=1);

namespace tests;


use boriskorobkov\leaflet\controls\Layers;
use boriskorobkov\leaflet\controls\Zoom;
use boriskorobkov\leaflet\layers\LayerGroup;
use boriskorobkov\leaflet\layers\Marker;
use boriskorobkov\leaflet\layers\Polygon;
use boriskorobkov\leaflet\layers\TileLayer;
use boriskorobkov\leaflet\LeafLet;
use boriskorobkov\leaflet\types\LatLng;
use yii\web\JsExpression;

class LeafLetTest extends TestCase
{
    public function testControls()
    {
        $zoomControl = new Zoom();
        $leafLet = new LeafLet(
            [
                'center' => new LatLng(['lat' => 51.508, 'lng' => -0.11]),
                'zoom' => 13
            ]
        );
        $leafLet->setControls([$zoomControl]);
        $this->assertEquals([$zoomControl], $leafLet->getControls());

        $layers = new Layers();
        $littleton = new Marker(['latLng' => new LatLng(['lat' => 39.61, 'lng' => -105.02])]);
        $denver = new Marker(['latLng' => new LatLng([ 'lat' => 39.74, 'lng' => -104.99])]);

        $group = new LayerGroup();
        $group->addLayer($littleton);
        $group->addLayer($denver);
        $layers->setOverlays(['cities'  => $group]);

        $leafLet->addControl($layers);
        $this->assertEquals([$zoomControl, $layers], $leafLet->getControls());

        $this->expectException('yii\base\InvalidArgumentException');
        $leafLet->setControls(['wrong']);
    }

    public function testInitException() {
        $this->expectException('yii\base\InvalidConfigException');
        $leafLet = new LeafLet();
    }

    public function testTileLayer() {
        $tileLayer = new TileLayer(
            [
                'map' => 'testMap',
                'urlTemplate' => 'https://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg',
                'clientOptions' => [
                    'attribution' => 'Tiles Courtesy of <a href="https://www.mapquest.com/" target="_blank">MapQuest</a> ' .
                        '<img src="https://developer.mapquest.com/content/osm/mq_logo.png">, ' .
                        'Map data &copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                    'subdomains' => '1234'
                ]
            ]
        );
        $leafLet = new LeafLet(
            [
                'tileLayer' => $tileLayer,
                'center' => new LatLng(['lat' => 51.508, 'lng' => -0.11]),
                'zoom' => 13
            ]
        );
        $this->assertEquals('testMap', $leafLet->name);
    }

    public function testLayerGroup() {
        $littleton = new Marker(['latLng' => new LatLng(['lat' => 39.61, 'lng' => -105.02])]);
        $denver = new Marker(['latLng' => new LatLng([ 'lat' => 39.74, 'lng' => -104.99])]);

        $group = new LayerGroup();
        $group->addLayer($littleton);
        $group->addLayer($denver);

        $leafLet = new LeafLet(
            [
                'center' => new LatLng(['lat' => 51.508, 'lng' => -0.11]),
                'zoom' => 13
            ]
        );

        $this->assertEquals([$group], $leafLet->addLayerGroup($group)->getLayerGroups());
        $this->assertEmpty($leafLet->clearLayerGroups()->getLayerGroups());
    }

    public function testJs() {
        $latLngs = [
            new LatLng(['lat' => 39.61, 'lng' => -105.02]),
            new LatLng(['lat' => 39.73, 'lng' => -104.88]),
            new LatLng(['lat' => 39.74, 'lng' => -104.99])
        ];

        $polygon = new Polygon();

        $polygon->setLatLngs($latLngs);
        $polygon->insertAtTheBottom = true;
        $littleton = new Marker(['name' => 'littleton', 'latLng' => new LatLng(['lat' => 39.61, 'lng' => -105.02])]);
        $denver = new Marker(['name' => 'denver', 'latLng' => new LatLng([ 'lat' => 39.74, 'lng' => -104.99])]);

        $group = new LayerGroup();
        $group->addLayer($littleton);
        $group->addLayer($denver);
        $leafLet = new LeafLet(
            [
                'center' => new LatLng(['lat' => 51.508, 'lng' => -0.11]),
                'zoom' => 13
            ]
        );

        $leafLet->addLayer($polygon);
        $leafLet->addLayerGroup($group);
        $leafLet->setJs(['var test = null;']);
        $leafLet->appendJs('var appendedJs = null;');
        $plugin = new TestPlugin([
            'name' => 'testPlugin',
            'clientEvents' => [
                'click' => new JsExpression('function(e){ console.log(e); }')
            ]
        ]);
        $leafLet->installPlugin($plugin);
        $actual = implode("\n", $leafLet->getJs());
        $expected = file_get_contents(__DIR__ . '/data/leaflet.js');
        $this->assertEquals($expected, $actual);
    }

    public function testWidget() {
        $view = \Yii::$app->getView();
        $content = $view->render('//map-leaflet');
        $actual = $view->render('//layouts/main', ['content' => $content]);
        $expected = file_get_contents(__DIR__ . '/data/test-map-leaflet.html');

        $this->assertEquals($expected, $actual);
    }

    public function testWidgetConfigHeightNumeric() {
        $view = \Yii::$app->getView();
        $content = $view->render('//map-leaflet-config', ['config' => [
            'height' => 200,
            'options' => [
                'id' => 'test-map',
                'style' => 'color:#000;'
            ],
        ]]);

        $actual = $view->render('//layouts/main', ['content' => $content]);
        $expected = file_get_contents(__DIR__ . '/data/test-map-leaflet.html');
        
        $this->assertEquals($expected, $actual);
    }

    public function testWidgetConfigHeightPx() {
        $view = \Yii::$app->getView();
        $content = $view->render('//map-leaflet-config', ['config' => [
            'height' => '200px',
            'options' => [
                'id' => 'test-map',
                'style' => 'color:#000;'
            ],
        ]]);

        $actual = $view->render('//layouts/main', ['content' => $content]);
        $expected = file_get_contents(__DIR__ . '/data/test-map-leaflet.html');

        $this->assertEquals($expected, $actual);
    }

    public function testWidgetConfigHeightPercent() {
        $view = \Yii::$app->getView();
        $content = $view->render('//map-leaflet-config', ['config' => [
            'height' => '100%',
            'options' => [
                'id' => 'test-map',
                'style' => 'color: #000;'
            ],
        ]]);
        $actual = $view->render('//layouts/main', ['content' => $content]);

        $expected = file_get_contents(__DIR__ . '/data/test-map-leaflet-height-percent.html');
        $this->assertEquals($expected, $actual);
    }

    public function testPlugins() {
        $leafLet = new LeafLet(
            [
                'center' => new LatLng(['lat' => 51.508, 'lng' => -0.11]),
                'zoom' => 13
            ]
        );
        $plugin = new TestPlugin(['name' => 'test']);

        $leafLet->installPlugin($plugin);
        $this->assertCount(1, $leafLet->getPlugins()->getInstalledPlugins());
        $this->assertEquals($plugin, $leafLet->getPlugins()->getPlugin('test'));
        $this->assertEquals($plugin, $leafLet->plugins->test);
        $this->assertEquals($leafLet->name, $plugin->map);
        $leafLet->removePlugin($plugin);
        $this->assertEmpty($leafLet->getPlugins()->getInstalledPlugins());

        $plugin->setName(null);
        $autogenerated = $plugin->getName(true);
        $this->assertNotNull($plugin->getName());
        $this->assertEquals($autogenerated, $plugin->getName());

        $this->expectException('yii\base\UnknownPropertyException');
        $leafLet->plugins->unknown;
    }
}
