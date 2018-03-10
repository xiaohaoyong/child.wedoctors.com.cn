<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace databackend\assets;
use yii\web\AssetBundle;
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class JuiAsset extends AssetBundle
{
    public $css = [
        'http://static.j.wedoctors.com.cn/metronic/plugins/jquery-ui/jquery-ui.min.css'                                                 ,
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}