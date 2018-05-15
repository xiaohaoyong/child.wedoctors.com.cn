<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/15
 * Time: 下午2:22
 */

namespace backend\assets;


use yii\web\AssetBundle;

class EasyAsset extends AssetBundle
{
    public $js = [
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-easypiechart/jquery.easypiechart.min.js'
    ];
    public $css= [
        'http://static.j.wedoctors.com.cn/metronic/css/components.css',
        'http://static.j.wedoctors.com.cn/metronic/css/plugins.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}