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
namespace boriskorobkov\leaflet\controls;

use boriskorobkov\leaflet\layers\LayerGroup;
use boriskorobkov\leaflet\layers\TileLayer;
use boriskorobkov\leaflet\LeafLet;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Layers The layers control gives users the ability to switch between different base layers and switch overlays on/off.
 *
 * @see https://leafletjs.com/reference.html#control-layers
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package boriskorobkov\leaflet\controls
 */
class Layers extends Control
{
    /**
     * @var \boriskorobkov\leaflet\layers\TileLayer[]
     */
    private array $_baseLayers = [];

    /**
     * @param mixed $baseLayers
     *
     * @throws \yii\base\InvalidArgumentException
     */
    public function setBaseLayers(array $baseLayers): void
    {
        foreach ($baseLayers as $key => $layer) {
            if (!($layer instanceof TileLayer)) {
                throw new InvalidArgumentException("All baselayers should be of type TileLayer ");
            }
            $this->_baseLayers[$key] = $layer;
        }
    }

    /**
     * @return \boriskorobkov\leaflet\layers\TileLayer[]
     */
    public function getBaseLayers(): array
    {
        return $this->_baseLayers;
    }

    /**
     * @return array of encoded base layers
     */
    public function getEncodedBaseLayers(): array
    {
        $layers = [];
        foreach ($this->getBaseLayers() as $key => $layer) {
            $layer->setName(null);
            $layers[$key] = $layer->encode(false);
        }
        return $layers;
    }

    /**
     * @var \boriskorobkov\leaflet\layers\Layer[]
     */
    private array $_overlays = [];

    /**
     * @param \boriskorobkov\leaflet\layers\LayerGroup[] $overlays
     *
     * @throws \yii\base\InvalidArgumentException
     */
    public function setOverlays(array $overlays): void
    {
        foreach ($overlays as $key => $overlay) {
            if (!($overlay instanceof LayerGroup)) {
                throw new InvalidArgumentException("All overlays should be of type LayerGroup");
            }
            $this->_overlays[$key] = $overlay;
        }
    }

    /**
     * @return \boriskorobkov\leaflet\layers\Layer[]
     */
    public function getOverlays(): array
    {
        return $this->_overlays;
    }

    /**
     * @return array of encoded overlays
     */
    public function getEncodedOverlays(): array
    {
        $overlays = [];
        /**
         * @var \boriskorobkov\leaflet\layers\LayerGroup $overlay
         */
        foreach ($this->getOverlays() as $key => $overlay) {
            $overlays[$key] = $overlay->oneLineEncode();
        }
        return $overlays;
    }

    /**
     * Returns the javascript ready code for the object to render
     * @return \yii\web\JsExpression
     */
    public function encode(bool $isAddSemicolon = true): JsExpression
    {
        $this->clientOptions['position'] = $this->position;
        $layers = $this->getEncodedBaseLayers();
        $overlays = $this->getEncodedOverlays();
        $options = $this->getOptions();
        $name = $this->getName();
        $map = $this->map;

        $layers = empty($layers) ? '{}' : Json::encode($layers, LeafLet::JSON_OPTIONS);
        $overlays = empty($overlays) ? '{}' : Json::encode($overlays, LeafLet::JSON_OPTIONS);

        $js = "L.control.layers($layers, $overlays, $options)" . ($map !== null ? ".addTo($map)" : "");
        if (!empty($name)) {
            $js = "var $name = $js" . ($isAddSemicolon ? ";" : "");
        } elseif ($isAddSemicolon) {
            $js .= ";";
        }
        return new JsExpression($js);
    }
}
