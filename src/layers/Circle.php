<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\leaflet\layers;

use yii\base\InvalidConfigException;
use yii\web\JsExpression;

/**
 * Circle a class for drawing circle overlays on a map.
 *
 * @see https://leafletjs.com/reference.html#circle
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package dosamigos\leaflet\layers
 */
/**
 * @property string $name
 */
class Circle extends Layer
{
    use LatLngTrait;
    use PopupTrait;

    /**
     * @var float Sets the radius of a circle. Units are in meters.
     */
    public float $radius = 0.0;

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        if ($this->getLatLng() === null) {
            throw new InvalidConfigException("'latLng' attribute cannot be empty.");
        }
    }

    /**
     * Returns the javascript ready code for the object to render
     * @return JsExpression
     */
    public function encode(bool $isAddSemicolon = true): JsExpression
    {
        $bounds = $this->getLatLng()->toArray(true);
        $radius = $this->radius;
        $options = $this->getOptions();
        $name = $this->name;
        $map = $this->map;
        $js = $this->bindPopupContent("L.circle($bounds, $radius, $options)") . ($map !== null ? ".addTo($map)" : "");
        $js .= $this->getEvents();
        if (!empty($name)) {
            $js = "var $name = $js" . ($isAddSemicolon ? ";" : "");
        } elseif ($isAddSemicolon) {
            $js .= ";";
        }

        return new JsExpression($js);
    }
}
