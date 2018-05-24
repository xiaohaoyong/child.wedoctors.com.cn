<?php

namespace databackend\assets;
use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class IndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/dashboard.js',
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset',
        'databackend\assets\MorrisAsset',
        'databackend\assets\DatabasesAsset'

    ];
}

