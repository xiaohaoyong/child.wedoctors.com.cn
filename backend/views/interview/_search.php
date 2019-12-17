<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\InterviewSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interview-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'prenatal_test') ?>

    <?= $form->field($model, 'pt_hospital') ?>

    <?= $form->field($model, 'pt_date') ?>

    <?= $form->field($model, 'prenatal') ?>

    <?php // echo $form->field($model, 'childbirth_hospital') ?>

    <?php // echo $form->field($model, 'childbirth_date') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php // echo $form->field($model, 'pt_value') ?>

    <?php // echo $form->field($model, 'week') ?>

    <?php // echo $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'childbirth_type') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
