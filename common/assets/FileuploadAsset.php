<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace common\assets;
use yii\web\AssetBundle;
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FileuploadAsset extends AssetBundle
{
    public $css = [
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/css/jquery.fileupload.css',
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/css/jquery.fileupload-ui.css',
    ];
    public $js= [
        "http://static.j.wedoctors.com.cn/metronic/plugins/fancybox/source/jquery.fancybox.pack.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/vendor/tmpl.min.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/vendor/load-image.min.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/jquery.iframe-transport.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/jquery.fileupload.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/jquery.fileupload-process.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/jquery.fileupload-image.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/jquery.fileupload-audio.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/jquery.fileupload-video.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/jquery.fileupload-validate.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/js/jquery.fileupload-ui.js",
        "http://static.j.wedoctors.com.cn/metronic/plugins/jquery-file-upload/form-fileupload.js",


    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}