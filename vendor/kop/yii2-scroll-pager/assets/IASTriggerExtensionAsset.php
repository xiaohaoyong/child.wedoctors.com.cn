<?php

namespace kop\y2sp\assets;

use yii\web\AssetBundle;


/**
 * Class IASTriggerExtensionAsset
 * @package kop\y2sp\assets
 */
class IASTriggerExtensionAsset extends AssetBundle
{

    public $sourcePath = '@vendor/webcreate/jquery-ias/src';

    public $js = [
        'extension/trigger.js'
    ];

    /**
     * @var array List of bundle class names that this bundle depends on.
     */
    public $depends = [
        'kop\y2sp\assets\InfiniteAjaxScrollAsset',
    ];

}
