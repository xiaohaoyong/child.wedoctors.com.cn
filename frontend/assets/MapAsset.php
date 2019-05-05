<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/5/5
 * Time: 上午10:30
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class MapAsset extends AssetBundle
{
    public $js = [
        'https://map.qq.com/api/js?v=2.exp&key=',
    ];
}