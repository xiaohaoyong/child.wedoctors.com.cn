<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserParentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-parent-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'mother') ?>

    <?= $form->field($model, 'mother_phone') ?>

    <?= $form->field($model, 'mother_id') ?>

    <?= $form->field($model, 'father') ?>

    <?php  echo $form->field($model, 'father_phone') ?>

    <?php // echo $form->field($model, 'father_birthday') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'field34') ?>

    <?php // echo $form->field($model, 'field33') ?>

    <?php // echo $form->field($model, 'field30') ?>

    <?php // echo $form->field($model, 'field29') ?>

    <?php // echo $form->field($model, 'field28') ?>

    <?php // echo $form->field($model, 'field12') ?>

    <?php // echo $form->field($model, 'field11') ?>

    <?php // echo $form->field($model, 'field1') ?>

    <?php // echo $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'county') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'fbirthday') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
