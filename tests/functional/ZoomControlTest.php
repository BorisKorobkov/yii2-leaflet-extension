<?php
declare(strict_types=1);

namespace tests;

use dosamigos\leaflet\controls\Zoom;

/**
 * @group controls
 */
class ZoomControlTest extends TestCase
{
    public function testEncode()
    {
        $zoom = new Zoom();
        $actual = $zoom->encode();
        $expected = 'L.control.zoom({"position":"topright"});';
        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithName() {
        $zoom = new Zoom(['name' => 'zoomName']);
        $actual = $zoom->encode();
        $expected = 'var zoomName = L.control.zoom({"position":"topright"});';
        $this->assertEquals($expected, $actual);
    }

    public function testEncodeWithMap() {
        $zoom = new Zoom(['map' => 'mapName']);
        $actual = $zoom->encode();
        $expected = 'L.control.zoom({"position":"topright"}).addTo(mapName);';
        $this->assertEquals($expected, $actual);
    }
}
