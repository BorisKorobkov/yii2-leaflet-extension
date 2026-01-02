<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\leaflet\types;

use dosamigos\leaflet\LeafLet;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Point represents a point with x and y coordinates in pixels.
 *
 * ```
 *  $map->panBy(new Point(['x' => 200, 'y' => '300']));
 * ```
 *
 * @see https://leafletjs.com/reference.html#point
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package dosamigos\leaflet\types
 */
class Point extends Type implements ArrayableInterface
{
    /**
     * @var float x coordinate
     */
    public float $x;

    /**
     * @var float y coordinate
     */
    public float $y;
    
    /**
     * @var bool if round is set to true, LetLeaf will round the x and y values.
     */
    public bool $round = false;

    /**
     * Initializes the class
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        if (!isset($this->x) || !isset($this->y)) {
            throw new InvalidConfigException("'x' or 'y' cannot be empty.");
        }
    }

    /**
     * @return \yii\web\JsExpression the js initialization code of the object
     */
    public function encode(): JsExpression
    {
        $x = $this->x;
        $y = $this->y;
        return new JsExpression("L.point($x, $y)"); // no semicolon
    }

    /**
     * Returns the point values as array
     *
     * @param bool $encode whether to return the array json_encoded or raw
     *
     * @return array|JsExpression
     */
    public function toArray($encode = false): mixed
    {
        $point = [$this->x, $this->y];
        return $encode ? new JsExpression(Json::encode($point, LeafLet::JSON_OPTIONS)) : $point;
    }
}
