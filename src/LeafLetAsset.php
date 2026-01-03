<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\leaflet;

use yii\web\AssetBundle;

/**
 * LeafLetAsset registers widget required files. Please, use the following in order to override bundles for CDN:
 *
 * ```
 *  return [
 *        // ...
 *        'components' => [
 *            'bundles' => [
 *                'dosamigos\leaflet\LeafLetAsset' => [
 *                    'sourcePath' => null,
 *                    'js' => [ 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js' ],
 *                    'css' => [ 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css' ]
 *                ]
 *            ]
 *        ]
 *    ]
 * ```
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 * @package dosamigos\leaflet
 */
class LeafLetAsset extends AssetBundle
{
    public $sourcePath = '@npm/leaflet/dist';

    public $css = [
        'leaflet.css'
    ];

    public $js = [
        'leaflet-src.js'
    ];
}