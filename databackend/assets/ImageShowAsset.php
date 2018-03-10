<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/19
 * Time: 16:45
 */
namespace databackend\assets;
use yii\web\AssetBundle;
class ImageShowAsset extends AssetBundle
{
    public $css=[
        'http://static.j.wedoctors.com.cn/metronic/plugins/select2/select2.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/select2/plugins-md.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/select2/plugins.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap-fileinput/css/bootstrap-fileinput.css'
    ];
    public $js=[
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap-fileinput/js/bootstrap-fileinput.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap/js/bootstrap.min.js',
        'http://static.j.wedoctors.com.cn/metronic/pages/components-dropdowns.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/select2/select2.min.js',
    ];
}