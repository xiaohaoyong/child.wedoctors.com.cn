<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/19
 * Time: 16:45
 */
namespace common\assets;
use yii\web\AssetBundle;
class ManyPagesAsset extends AssetBundle
{
    public $css=[
        'http://static.j.wedoctors.com.cn/metronic/plugins/select2/select2.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/select2/plugins-md.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/select2/plugins.css',
    ];
    public $js=[
      //  'http://static.j.wedoctors.com.cn/metronic/plugins/jquery.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/select2/select2.min.js',
        'http://static.j.wedoctors.com.cn/metronic/pages/form-wizard.js',



        'http://static.j.wedoctors.com.cn/metronic/plugins/typeahead/handlebars.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap-touchspin/bootstrap.touchspin.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/typeahead/typeahead.bundle.min.js',
        'http://static.j.wedoctors.com.cn/metronic/pages/components-form-tools.js',


        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-validation/js/jquery.validate.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
    ];
}