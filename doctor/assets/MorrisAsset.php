<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/22 0022
 * Time: 1:13
 */

namespace doctor\assets;


use yii\web\AssetBundle;

class MorrisAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css=[
        'morris/morris.css'
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
        'morris/morris.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',

    ];
}