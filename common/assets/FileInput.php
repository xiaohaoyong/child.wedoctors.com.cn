<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2018
 * @package yii2-widgets
 * @subpackage yii2-widget-fileinput
 * @version 1.0.8
 */

namespace common\assets;

use kartik\file\DomPurifyAsset;
use kartik\file\FileInputThemeAsset;
use kartik\file\PiExifAsset;
use kartik\file\SortableAsset;
use yii\helpers\ArrayHelper;
use kartik\base\InputWidget;


class FileInput extends \kartik\file\FileInput
{
    /**
     * Registers the asset bundle and locale
     * @throws \yii\base\InvalidConfigException
     */
    public function registerAssetBundle()
    {
        $view = $this->getView();
        $this->pluginOptions['resizeImage'] = $this->resizeImages;
        $this->pluginOptions['autoOrientImage'] = $this->autoOrientImages;
        if ($this->resizeImages || $this->autoOrientImages) {
            PiExifAsset::register($view);
        }
        if (empty($this->pluginOptions['theme']) && $this->isBs4()) {
            $this->pluginOptions['theme'] = 'fas';
        }
        $theme = ArrayHelper::getValue($this->pluginOptions, 'theme');
        if (!empty($theme) && in_array($theme, self::$_themes)) {
            FileInputThemeAsset::register($view)->addTheme($theme);
        }
        if ($this->sortThumbs) {
            SortableAsset::register($view);
        }

        \common\assets\FileInputAsset::register($view)->addLanguage($this->language, '', 'js/locales');
    }
}
