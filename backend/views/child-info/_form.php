<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ChildInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="child-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userid')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthday')->textInput() ?>

    <?= $form->field($model, 'createtime')->textInput() ?>

    <?= $form->field($model, 'gender')->textInput() ?>

    <?= $form->field($model, 'source')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
