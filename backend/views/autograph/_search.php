<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AutographSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="autograph-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'createtime') ?>

    <?= $form->field($model, 'img') ?>

    <?= $form->field($model, 'loginid') ?>

    <?= $form->field($model, 'userid') ?>

    <?php // echo $form->field($model, 'doctorid') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
