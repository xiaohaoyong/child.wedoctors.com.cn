<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/19
 * Time: 16:45
 */
namespace common\assets;
use yii\web\AssetBundle;
class JqAlert extends AssetBundle
{

    
    public $css=[
        'http://web.child.wedoctors.com.cn/js/jq_alert/jquery-confirm.css',
    ];
    public $js=[
        //'http://static.j.wedoctors.com.cn/metronic/plugins/jquery.min.js',
        'http://web.child.wedoctors.com.cn/js/jq_alert/jquery-confirm.js' ,
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}