<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/22 0022
 * Time: 1:13
 */

namespace databackend\assets;


use yii\web\AssetBundle;

class DatabasesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css=[
        'datatables/dataTables.bootstrap4.min.css'
    ];
    public $js = [
        'datatables/jquery.dataTables.min.js',
        'datatables/dataTables.bootstrap4.min.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',

    ];
}