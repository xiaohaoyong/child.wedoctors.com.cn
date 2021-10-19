<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\AppointHpvSettingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-hpv-setting-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'doctorid') ?>

    <?= $form->field($model, 'week1') ?>

    <?= $form->field($model, 'week2') ?>

    <?= $form->field($model, 'week3') ?>

    <?php // echo $form->field($model, 'week4') ?>

    <?php // echo $form->field($model, 'week5') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
