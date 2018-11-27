<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\UserDoctorAppointSearchModels */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-doctor-appoint-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'doctorid') ?>

    <?= $form->field($model, 'weeks') ?>

    <?= $form->field($model, 'cycle') ?>

    <?= $form->field($model, 'delay') ?>

    <?= $form->field($model, 'type1_num') ?>

    <?php // echo $form->field($model, 'type2_num') ?>

    <?php // echo $form->field($model, 'type3_num') ?>

    <?php // echo $form->field($model, 'type4_num') ?>

    <?php // echo $form->field($model, 'type5_num') ?>

    <?php // echo $form->field($model, 'type6_num') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
