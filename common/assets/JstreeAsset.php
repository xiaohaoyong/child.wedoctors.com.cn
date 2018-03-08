<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/26
 * Time: 10:52
 */

namespace common\assets;
use yii\web\AssetBundle;
class JstreeAsset extends AssetBundle
{
    public $css=[
        'http://static.j.wedoctors.com.cn/metronic/plugins/jstree/dist/themes/default/style.min.css' ,

    ];
    public $js=[
        'http://static.j.wedoctors.com.cn/metronic/plugins/jstree/dist/jstree.min.js',

    ];
}