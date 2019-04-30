<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="header">
    <div class="main">
        <div class="logo"><img src="/img/index_logo.png?t=234" width="107" height="37"></div>
        <div class="navigation">
            <div class="li <?=\Yii::$app->controller->action->id=='index'?'on':''?>"><a href="/">儿童健康管理SaaS云服务</a></div>
            <div class="li <?=\Yii::$app->controller->action->id=='view'?'on':''?>"><a href="/index/view">开放 合作 共享</a></div>
            <div class="li <?=\Yii::$app->controller->action->id=='about'?'on':''?>"><a href="/index/about">关于儿宝宝</a></div>
        </div>
    </div>
</div>

<?= Alert::widget() ?>
<?= $content ?>
<div class="footer">
    <div class="main">
        <div class="list">
            <div class="list1">
                <div class="item title">服务与方案</div>
                <div class="item">基层医疗机构端</div>
                <div class="item">卫生监督管理端</div>
                <div class="item">服务开放平台</div>
            </div>
            <div class="list2">
                <div class="item title">共享合作</div>
                <div class="item">北京红十字基金</div>
            </div>
        </div>
        <div class="qcode">
            <div class="img">
                <img src="/img/footer_qcode_wechat.png" width="146" height="146">
                <div class="title">微信公众号</div>
            </div>
            <div class="img">
                <img src="/img/footer_qcode_xiao.png" width="158" height="158">
                <div class="title">微信小程序</div>
            </div>
        </div>
    </div>
    <div class="ftCon">
        <div class="text">北京（微医）健康科技有限公司  服务咨询及合作联系热线：18201599388</div>
        <div class="text">CopyRight 2017 - 2019 wedoctors.com.cn 版权所有 京ICP备 16028326 号-1</div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
