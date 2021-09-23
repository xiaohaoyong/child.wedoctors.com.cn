<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\AppointHpvSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'doctorid') ?>

    <?= $form->field($model, 'createtime') ?>

    <?= $form->field($model, 'appoint_time') ?>

    <?php // echo $form->field($model, 'appoint_date') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'childid') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'loginid') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'cancel_type') ?>

    <?php // echo $form->field($model, 'push_state') ?>

    <?php // echo $form->field($model, 'mode') ?>

    <?php // echo $form->field($model, 'vaccine') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'orderid') ?>

    <?php // echo $form->field($model, 'street') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
