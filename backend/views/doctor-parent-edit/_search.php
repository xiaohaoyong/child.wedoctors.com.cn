<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DoctorParentEditSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctor-parent-edit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'doctorid') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'childid') ?>

    <?= $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'img') ?>

    <?php // echo $form->field($model, 'teamid') ?>

    <?php // echo $form->field($model, 'level') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
