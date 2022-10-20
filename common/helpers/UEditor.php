<?php

/**
 * @link https://github.com/BigKuCha/yii2-ueditor-widget
 * @link http://ueditor.baidu.com/website/index.html
 */
namespace common\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\InputWidget;

class UEditor extends \kucha\ueditor\UEditor
{

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript()
    {
        UEditorAsset::register($this->view);
        $clientOptions = Json::encode($this->clientOptions);
        $script = "UE.getEditor('" . $this->id . "', " . $clientOptions . ");";
        $this->view->registerJs($script, View::POS_READY);
    }
}
