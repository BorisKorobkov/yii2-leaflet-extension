<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\leaflet;

use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\web\View;

class PluginManager extends Component
{
    /**
     * @var Plugin[]
     */
    private array $_plugins = [];

    /**
     * Check whether we have a plugin installed with that name previous firing up the call
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name): mixed
    {
        if (isset($this->_plugins[$name])) {
            return $this->_plugins[$name];
        }
        return parent::__get($name);
    }

    /**
     * Installs a plugin
     *
     * @param Plugin $plugin
     *
     * @return void
     */
    public function install(Plugin $plugin): void
    {
        $name = $plugin->getName(true);
        $this->_plugins[$name] = $plugin;
    }

    /**
     * Removes a plugin
     *
     * @param Plugin $plugin
     *
     * @return mixed the value of the element if found, default value otherwise
     */
    public function remove(Plugin $plugin): mixed
    {
        $name = $plugin->getName();
        if ($name !== null) {
            return ArrayHelper::remove($this->_plugins, $name);
        }
        return null;
    }

    /**
     * @param View $view
     * Registers plugin bundles
     * @return void
     */
    public function registerAssetBundles(View $view): void
    {
        foreach ($this->_plugins as $plugin) {
            $plugin->registerAssetBundle($view);
        }
    }

    /**
     * @return array of installed plugins
     */
    public function getInstalledPlugins(): array
    {
        return $this->_plugins;
    }

    /**
     * Returns an installed plugin by name
     *
     * @param string $name
     *
     * @return Plugin|null
     */
    public function getPlugin($name): ?Plugin
    {
        return $this->_plugins[$name] ?? null;
    }
}
