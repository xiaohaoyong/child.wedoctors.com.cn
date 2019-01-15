<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\InterviewSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interview-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>



    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'prenatal_test')->dropDownList(\common\models\Interview::$prenatal_Text,['prompt'=>'请选择']) ?>
    <?= $form->field($model, 'prenatal')->dropDownList(\common\models\Interview::$prenatalText,['prompt'=>'请选择']) ?>

    <?php // echo $form->field($model, 'childbirth_hospital') ?>

    <?php // echo $form->field($model, 'childbirth_date') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php  echo $form->field($model, 'pt_value')->dropDownList(\common\models\Interview::$prenatalValueText,['prompt'=>'请选择']) ?>

    <?php // echo $form->field($model, 'week') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
