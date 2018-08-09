<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'childid') ?>

    <?= $form->field($model, 'touserid') ?>

    <?= $form->field($model, 'artid') ?>

    <?= $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php // echo $form->field($model, 'level') ?>

    <?php // echo $form->field($model, 'child_type') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
