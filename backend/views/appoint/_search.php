<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AppointSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'doctorid') ?>


    <?= $form->field($model, 'appoint_time') ?>

    <?php  echo $form->field($model, 'appoint_date') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php  echo $form->field($model, 'childid') ?>

    <?php  echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php  echo $form->field($model, 'loginid') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'cancel_type') ?>

    <?php // echo $form->field($model, 'push_state') ?>

    <?php // echo $form->field($model, 'mode') ?>

    <?php // echo $form->field($model, 'vaccine') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>