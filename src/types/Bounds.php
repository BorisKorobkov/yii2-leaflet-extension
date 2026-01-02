<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\leaflet\types;

use yii\base\InvalidConfigException;
use yii\web\JsExpression;

/**
 * Bounds represents a rectangular area in pixel coordinates.
 *
 * @see https://leafletjs.com/reference.html#bounds
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package dosamigos\leaflet\types
 */

/**
 * @property Point $min
 * @property Point $max
 */
class Bounds extends Type implements ArrayableInterface
{

    /**
     * @var Point the top left corner of the rectangle
     */
    private ?Point $_min = null;

    /**
     * @var Point the bottom right corner of the rectangle
     */
    private ?Point $_max = null;

    /**
     * @param Point $max
     */
    public function setMax(Point $max): void
    {
        $this->_max = $max;
    }

    /**
     * @return Point
     */
    public function getMax(): ?Point
    {
        return $this->_max;
    }

    /**
     * @param Point $min
     */
    public function setMin(Point $min): void
    {
        $this->_min = $min;
    }

    /**
     * @return Point
     */
    public function getMin(): ?Point
    {
        return $this->_min;
    }

    /**
     * Initializes the object
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        if (empty($this->min) || empty($this->max)) {
            throw new InvalidConfigException("'min' and 'max' attributes cannot be empty.");
        }
    }

    /**
     * @return \yii\web\JsExpression the js initialization code of the object
     */
    public function encode(): JsExpression
    {
        $min = $this->getMin()->toArray(true);
        $max = $this->getMax()->toArray(true);

        return new JsExpression("L.bounds($min, $max)");
    }

    /**
     * Converts the object into an array.
     *
     * @param bool $encode whether to return the array json_encoded or raw
     *
     * @return array the array representation of this object
     */
    public function toArray($encode = false): mixed
    {
        $min = $this->getMin()->toArray($encode);
        $max = $this->getMax()->toArray($encode);
        return $encode ? "[$min, $max]" : [$min, $max];
    }


}
