<?php
declare(strict_types=1);

namespace tests;

use dosamigos\leaflet\Plugin;
use yii\web\JsExpression;

class TestPlugin extends Plugin
{
    /**
     * Returns the plugin name
     * @return string
     */
    public function getPluginName(): string
    {
        return 'plugin:TestPlugin';
    }

    /**
     * Registers plugin asset bundle
     *
     * @param \yii\web\View $view
     *
     * @return void
     */
    public function registerAssetBundle(\yii\web\View $view): void
    {
        TestAsset::register($view);
    }

    /**
     * Returns the javascript ready code for the object to render
     * @return \yii\web\JsExpression
     */
    public function encode(bool $isAddSemicolon = true): JsExpression
    {
        $name = $this->getName();
        $options = $this->getOptions();
        $js = "L.TestPlugin($options)";
        $js .= $this->getEvents();
        if ($name) {
            $js = "var $name = $js" . ($isAddSemicolon ? ";" : "");
        } elseif ($isAddSemicolon) {
            $js .= ";";
        }
        return new JsExpression($js);
    }
}
