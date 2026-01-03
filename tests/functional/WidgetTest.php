<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */
/**
 * @copyright Copyright (c) 2026 Boris Korobkov
 * @link https://github.com/BorisKorobkov
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace tests;

use boriskorobkov\leaflet\widgets\Map;

/**
 * @group widgets
 */
class WidgetTest extends TestCase
{
    public function testWidget() {
        $view = \Yii::$app->getView();
        $content = $view->render('//map-widget');
        $actual = $view->render('//layouts/main', ['content' => $content]);
        $expected = file_get_contents(__DIR__ . '/data/test-map-widget.html');
        $this->assertEquals($expected, $actual);
    }

    public function testException() {
        $this->expectException('yii\base\InvalidConfigException');
        $widget = Map::begin();
    }
}
