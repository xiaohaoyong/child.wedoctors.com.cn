<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = "异常信息";
$this->registerCssFile("@web/css/mui.min.css");
$this->registerCssFile("@web/css/mui.picker.min.css");
$this->registerCssFile("@web/css/common.css");
$this->registerCssFile("@web/css/new_login.css");
?>
<script>
    window.onresize = function () {
        var iWidth = document.documentElement.clientWidth;
        iWidth = iWidth > 414 ? 540 : iWidth;
        document.getElementsByTagName('html')[0].style.fontSize = iWidth / 16 + 'px';
    }
    window.onresize();
</script>
<style type="text/css">
    .ui-notice {
        width: 100%;
        height: 100%;
        z-index: 99;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-box-pack: center;
        -webkit-box-align: center;
        position: absolute;
        text-align: center;
    }
    .ui-notice p {
        font-size: 16px;
        line-height: 20px;
        color: #bbbbbb;
        text-align: center;
        padding: 0 15px;
    }
    .ui-notice span{
        font-size: 100px;
        line-height: 100px;
        color: rgba(0, 0, 0, 0.3);
        margin-bottom: 20px;
    }
</style>
<section class="ui-notice">
    <span class="mui-icon mui-icon-info"></span>
    <p><?= nl2br(Html::encode($message)) ?></p>
</section>