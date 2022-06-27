<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap\Modal;

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
<?= Alert::widget() ?>
<div class="header">
    <div class="main">
        <div class="logo"><img src="/img/index_logo.png?t=234" width="107" height="37"></div>
        <div class="navigation">
            <div class="li <?= \Yii::$app->controller->action->id == 'index' ? 'on' : '' ?>"><a
                        href="/">基层智慧管理SaaS云服务</a></div>
            <div class="li <?= \Yii::$app->controller->action->id == 'view' ? 'on' : '' ?>"><a href="/index/view">开放 合作
                    共享</a></div>
            <div class="li <?= \Yii::$app->controller->action->id == 'about' ? 'on' : '' ?>"><a href="/index/about">关于儿宝宝</a>
            </div>
        </div>
    </div>
</div>
<?= $content ?>
<div class="footer">
    <div class="main">
        <div class="list">
            <div class="list1" style="cursor:pointer;"  data-toggle='modal' data-target='#create-modal'>
                <div class="item title">服务与方案</div>
                <div class="item">基层医疗机构端</div>
                <div class="item">卫生监督管理端</div>
                <div class="item">服务开放平台</div>
            </div>
            <div class="list2">
                <div class="item title">共享合作</div>
                <div class="item">北京市红十字基金会</div>
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
        <div class="text">微医（北京）健康科技有限公司</div>
        <div class="text">CopyRight 2017 - 2022 wedoctors.com.cn 版权所有 <a href="https://beian.miit.gov.cn"> 京ICP备 16028326 号-1</a></div>
    </div>
</div>
<?php
Modal::begin([
    'id' => 'create-modal',
    'header' => '<h4 class="modal-title">联系方式</h4>',
]);
$Customer = new \common\models\Customer();
$form = \yii\widgets\ActiveForm::begin([
    'id' => 'form-id',
    'enableAjaxValidation' => true,
    'validationUrl' => ['customer/validate-form'],
    'action' => ['customer/put'],
]);
echo $form->field($Customer, 'name')->textInput(['placeholder' => '联系人'])->label(false);
echo $form->field($Customer, 'phone')->textInput(['placeholder' => '联系电话'])->label(false);
echo $form->field($Customer, 'title')->textarea(['placeholder' => '机构/单位名称', 'rows' => '6'])->label(false);
echo Html::submitButton('提交', ['class' => 'btn btn-red']);

\yii\widgets\ActiveForm::end();
Modal::end();
?>

<?php
$updateJs = <<<JS
    $(function(){ 
$(document).on('beforeSubmit', 'form#form-id', function () { 
    var form = $(this); 
    //返回错误的表单信息 
    if (form.find('.has-error').length) 
    { 
      return false; 
    } 
    //表单提交 
    $.ajax({ 
      url  : form.attr('action'), 
      type  : 'post', 
      data  : form.serialize(), 
      success: function (response){ 
        if(response.success){ 
          alert('保存成功'); 
          window.location.reload(); 
        } 
      }, 
      error : function (){ 
        alert('系统错误'); 
        return false; 
      } 
    }); 
    return false; 
  }); 
}); 
JS;
$this->registerJs($updateJs);
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
