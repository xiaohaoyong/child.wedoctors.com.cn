<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class DateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/mobileSelect.css'
    ];
    public $js = [
        'js/mobileSelect.js',
        'js/selectDate.js',
    ];
    public $depends = [
        'frontend\assets\WebAsset',
    ];
}
