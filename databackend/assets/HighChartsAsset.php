<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/27
 * Time: 17:04
 */

namespace databackend\assets;
use yii\web\AssetBundle;
class HighChartsAsset extends AssetBundle
{

    public $js=[
        'http://static.j.wedoctors.com.cn/metronic/plugins/highcharts.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/exporting.js',
    ];
}