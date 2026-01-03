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

/**
 * AssetManager
 */
class AssetManager extends \yii\web\AssetManager
{
    private $_hashes = [];
    private $_counter = 0;

    /**
     * @inheritdoc
     */
    public function hash($path): string
    {
        if (!isset($this->_hashes[$path])) {
            $this->_hashes[$path] = $this->_counter++;
        }

        return (string)$this->_hashes[$path];
    }
}
