<?php

namespace kop\y2sp\assets;

use yii\web\AssetBundle;


/**
 * Class IASPagingExtensionAsset
 * @package kop\y2sp\assets
 */
class IASPagingExtensionAsset extends AssetBundle
{

    public $sourcePath = '@vendor/webcreate/jquery-ias/src';

    public $js = [
        'extension/paging.js'
    ];

    /**
     * @var array List of bundle class names that this bundle depends on.
     */
    public $depends = [
        'kop\y2sp\assets\InfiniteAjaxScrollAsset',
    ];

}
