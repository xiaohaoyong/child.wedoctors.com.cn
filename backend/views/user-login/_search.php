<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserLoginSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-login-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'openid') ?>

    <?= $form->field($model, 'logintime') ?>

    <?= $form->field($model, 'xopenid') ?>

    <?php // echo $form->field($model, 'unionid') ?>

    <?php // echo $form->field($model, 'hxusername') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
