<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BabyToolTagSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="baby-tool-tag-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tag') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'week') ?>

    <?= $form->field($model, 'week_s') ?>

    <?php // echo $form->field($model, 'mother_num') ?>

    <?php // echo $form->field($model, 'father_num') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
