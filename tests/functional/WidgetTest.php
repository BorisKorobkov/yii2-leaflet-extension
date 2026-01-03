<?php
declare(strict_types=1);

/**
 *
 * WidgetTest.php
 *
 * Date: 28/03/15
 * Time: 10:32
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 */

namespace tests;

use dosamigos\leaflet\widgets\Map;

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
