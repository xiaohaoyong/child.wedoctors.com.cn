<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WeOpenidSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="we-openid-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>


    <?= $form->field($model, 'openid') ?>

    <?= $form->field($model, 'docpartimeS')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',

        'todayHighlight' => true
    ]])?>
    <?= $form->field($model, 'docpartimeE')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',

        'todayHighlight' => true
    ]])?>
    <?= $form->field($model, 'doctorid') ?>

    <?= $form->field($model, 'level')->dropDownList([0=>'未签约',1=>'已签约'],['prompt'=>'请选择']) ?>

    <?php  echo $form->field($model, 'unionid') ?>

    <?php  echo $form->field($model, 'xopenid') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
