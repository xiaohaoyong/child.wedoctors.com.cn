<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/19
 * Time: 16:45
 */
namespace databackend\assets;
use yii\web\AssetBundle;
use \yii\web\View;
class SearchSelectAsset extends AssetBundle
{
    public $css=[
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-nestable/jquery.nestable.css',
    ];
    public $js=[
        ['http://static.j.wedoctors.com.cn/metronic/plugins/jquery-1.11.0.min.js', 'position' => View::POS_HEAD],
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-nestable/jquery.nestable.js',
        'http://static.j.wedoctors.com.cn/metronic/pages/ui-nestable.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap/js/bootstrap.min.js',
    ];
}