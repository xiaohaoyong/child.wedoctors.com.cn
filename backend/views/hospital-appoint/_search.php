<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\HospitalAppointSearchModels */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospital-appoint-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'doctorid')->dropdownList(\common\models\UserDoctor::find()->select('name')->indexBy('userid')->column(),['prompt'=>'请选择']) ?>

    <?= $form->field($model, 'cycle') ?>

    <?= $form->field($model, 'delay') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'weeks') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
