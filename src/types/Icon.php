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

namespace boriskorobkov\leaflet\types;

use boriskorobkov\leaflet\LeafLet;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Icon represents an icon to provide when creating a marker.
 *
 * @see https://leafletjs.com/reference.html#icon
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package boriskorobkov\leaflet\types
 */
class Icon extends Type
{
    /**
     * @var string the variable name. If not null, then the js icon creation script
     * will be returned as a variable:
     *
     * ```
     * var iconName = L.icon({...});
     * // after it can be shared among other markers
     * L.marker({icon: iconName, ...).addTo(map);
     * L.marker({icon: iconName, ...).addTo(map);
     * ```
     * If null, the js icon creation script will be returned to be used as constructor so it can be used within another
     * constructor options:
     *
     * ```
     * L.marker({icon: L.icon({...}), ...).addTo(map);
     * ```
     */
    public ?string $name = null;

    /**
     * @var string (required) the URL to the icon image (absolute or relative to your script path).
     */
    public ?string $iconUrl = null;

    /**
     * @var string the URL to a retina sized version of the icon image (absolute or relative to your script path). Used
     * for Retina screen devices.
     */
    public ?string $iconRetinaUrl = null;

    /**
     * @var string the URL to the icon shadow image. If not specified, no shadow image will be created.
     */
    public ?string $shadowUrl = null;

    /**
     * @var string the URL to the retina sized version of the icon shadow image. If not specified, no shadow image will
     * be created. Used for Retina screen devices.
     */
    public ?string $shadowRetinaUrl = null;

    /**
     * @var string a custom class name to assign to both icon and shadow images. Empty by default.
     */
    public ?string $className = null;

    /**
     * @var Point size of the icon image in pixels.
     */
    private ?Point $_iconSize = null;
    /**
     * @var Point the coordinates of the "tip" of the icon (relative to its top left corner). The icon will be aligned so
     * that this point is at the marker's geographical location. Centered by default if size is specified, also can be
     * set in CSS with negative margins.
     */
    private ?Point $_iconAnchor = null;

    /**
     * @var Point size of the shadow image in pixels.
     */
    private ?Point $_shadowSize = null;

    /**
     * @var Point the coordinates of the "tip" of the shadow (relative to its top left corner) (the same as iconAnchor
     * if not specified).
     */
    private ?Point $_shadowAnchor = null;
    
    /**
     * @var Point the coordinates of the point from which popups will "open", relative to the icon anchor.
     */
    private ?Point $_popupAnchor = null;

    /**
     * @param Point $iconAnchor
     */
    public function setIconAnchor(Point $iconAnchor): void
    {
        $this->_iconAnchor = $iconAnchor;
    }

    /**
     * @return Point
     */
    public function getIconAnchor(): ?Point
    {
        return $this->_iconAnchor;
    }

    /**
     * @param Point $iconSize
     */
    public function setIconSize(Point $iconSize): void
    {
        $this->_iconSize = $iconSize;
    }

    /**
     * @return Point
     */
    public function getIconSize(): ?Point
    {
        return $this->_iconSize;
    }

    /**
     * @param Point $popupAnchor
     */
    public function setPopupAnchor(Point $popupAnchor): void
    {
        $this->_popupAnchor = $popupAnchor;
    }

    /**
     * @return Point
     */
    public function getPopupAnchor(): ?Point
    {
        return $this->_popupAnchor;
    }

    /**
     * @param Point $shadowAnchor
     */
    public function setShadowAnchor(Point $shadowAnchor): void
    {
        $this->_shadowAnchor = $shadowAnchor;
    }

    /**
     * @return Point
     */
    public function getShadowAnchor(): ?Point
    {
        return $this->_shadowAnchor;
    }

    /**
     * @param Point $shadowSize
     */
    public function setShadowSize(Point $shadowSize): void
    {
        $this->_shadowSize = $shadowSize;
    }

    /**
     * @return Point
     */
    public function getShadowSize(): ?Point
    {
        return $this->_shadowSize;
    }

    /**
     * Initializes the object
     * @throws \yii\base\InvalidConfigException
     */
    public function init(): void
    {
        if (empty($this->iconUrl)) {
            throw new InvalidConfigException("'iconUrl' attribute cannot be empty.");
        }
    }

    /**
     * @return string the js initialization code of the object
     */
    public function encode(): JsExpression
    {
        $options = Json::encode($this->getOptions(), LeafLet::JSON_OPTIONS);

        $js = "L.icon($options)";
        if ($this->name) {
            $js = "var $this->name = $js;";
        }
        return new JsExpression($js);
    }

    /**
     * @return array the configuration options of the array
     */
    public function getOptions(): array
    {
        $options = [];
        $class = new \ReflectionClass(__CLASS__);
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $name = $property->getName();
                $options[$name] = $this->$name;
            }
        }
        foreach (['iconAnchor', 'iconSize', 'popupAnchor', 'shadowAnchor', 'shadowSize'] as $property) {
            $point = $this->$property;
            if ($point instanceof Point) {
                $options[$property] = $point->toArray(true);
            }
        }
        return array_filter($options);
    }
}
