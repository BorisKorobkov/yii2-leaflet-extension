<?php
declare(strict_types=1);

namespace tests;

use yii\web\AssetBundle;

class TestAsset extends AssetBundle
{
    public $sourcePath = '@tests/data';

    public $js = [
        'empty-test.js'
    ];
}
