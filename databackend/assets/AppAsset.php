<?php

namespace databackend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'http://static.j.wedoctors.com.cn/metronic/plugins/font-awesome/css/font-awesome.min.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/simple-line-icons/simple-line-icons.min.css',
        'http://static.j.wedoctors.com.cn/metronic/css/components.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap/css/bootstrap.min.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/uniform/css/uniform.default.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'http://static.j.wedoctors.com.cn/metronic/css/plugins.css',
        'http://static.j.wedoctors.com.cn/metronic/layout/css/layout_data.css',
        'http://static.j.wedoctors.com.cn/metronic/layout/css/themes/darkblue.css',
        'http://static.j.wedoctors.com.cn/metronic/layout/css/custom.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-ui/jquery-ui.min.css',

    ];
    public $js = [
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-migrate.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-ui/jquery-ui.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap/js/bootstrap.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery.blockui.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/backstretch/jquery.backstretch.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-easypiechart/jquery.easypiechart.min.js',

        //        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery.cokie.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/uniform/jquery.uniform.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'http://static.j.wedoctors.com.cn/metronic/plugins/bootbox/bootbox.min.js',
        'http://static.j.wedoctors.com.cn/metronic/layout/scripts/metronic.js',
        'http://static.j.wedoctors.com.cn/metronic/layout/scripts/layout.js',
        'http://static.j.wedoctors.com.cn/metronic/layout/scripts/quick-sidebar.js',
        'http://static.j.wedoctors.com.cn/metronic/layout/scripts/demo.js',

    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
