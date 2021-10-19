<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\AppointHpvSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-hpv-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'cratettime') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php // echo $form->field($model, 'doctorid') ?>

    <?php // echo $form->field($model, 'vid') ?>

    <?php // echo $form->field($model, 'img') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
