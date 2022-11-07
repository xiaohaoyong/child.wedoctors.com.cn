<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SigningRecordSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="signing-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'type')->dropDownList(['1'=>'孕妈','2'=>'宝宝'],['prompt'=>'请选择']); ?>

    <?= $form->field($model, 'sign_item_id_from') ?>

    <?= $form->field($model, 'sign_item_id_to') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'info_pics') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
