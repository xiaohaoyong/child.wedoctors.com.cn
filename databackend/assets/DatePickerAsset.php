<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/19
 * Time: 16:45
 */
namespace databackend\assets;
use yii\web\AssetBundle;
class DatePickerAsset extends AssetBundle
{
    public $css=[
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap/css/bootstrap.min.css',

        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-ui/jquery-ui.min.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'
    ];
    public $js=[
        //'http://static.j.wedoctors.com.cn/metronic/plugins/jquery.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-ui/jquery-ui.min.js' ,
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap/js/bootstrap.js',

        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js',

    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}