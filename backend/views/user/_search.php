<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'level') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'source') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
