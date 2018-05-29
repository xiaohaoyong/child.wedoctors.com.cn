<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserDoctorSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-doctor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'sex') ?>

    <?= $form->field($model, 'age') ?>

    <?= $form->field($model, 'birthday') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'hospitalid') ?>

    <?php // echo $form->field($model, 'subject_b') ?>

    <?php // echo $form->field($model, 'subject_s') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'intro') ?>

    <?php // echo $form->field($model, 'avatar') ?>

    <?php // echo $form->field($model, 'skilful') ?>

    <?php // echo $form->field($model, 'idnum') ?>

    <?php // echo $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'county') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'atitle') ?>

    <?php // echo $form->field($model, 'otype') ?>

    <?php // echo $form->field($model, 'authimg') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
