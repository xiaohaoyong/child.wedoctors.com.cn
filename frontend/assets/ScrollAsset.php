<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/5/5
 * Time: 上午10:30
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class ScrollAsset extends AssetBundle
{
    public $js = [
        'http://static.j.wedoctors.com.cn/js/ant-widget.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}