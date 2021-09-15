<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\HospitalFormSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospital-form-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sign1') ?>

    <?= $form->field($model, 'sign2') ?>

    <?= $form->field($model, 'sign3') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'ratio1') ?>

    <?php // echo $form->field($model, 'ratio2') ?>

    <?php // echo $form->field($model, 'appoint_num') ?>

    <?php // echo $form->field($model, 'other_appoint_num') ?>

    <?php  echo $form->field($model, 'doctorid') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
