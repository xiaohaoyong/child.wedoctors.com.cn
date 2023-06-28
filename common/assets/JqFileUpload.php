<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2016/9/19
 * Time: 16:45
 */
namespace common\assets;
use yii\web\AssetBundle;
class JqFileUpload extends AssetBundle
{
    public $css=[
        'https://web.child.wedoctors.com.cn/js/jq_file_upload/css/jquery.upload.css',
    ];
    public $js=[
        //'http://static.j.wedoctors.com.cn/metronic/plugins/jquery.min.js',
        'https://web.child.wedoctors.com.cn/js/jq_file_upload/js/jquery.upload.js' ,
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}