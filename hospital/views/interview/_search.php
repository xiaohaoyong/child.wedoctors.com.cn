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

    <?= $form->field($model, 'field5s')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'field5e')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'field15s')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'field15e')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'childbirth_dates')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'childbirth_datee')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>

    <?= $form->field($model, 'prenatal_test')->dropDownList(\common\models\Interview::$prenatal_Text,['prompt'=>'请选择']) ?>
    <?= $form->field($model, 'prenatal')->dropDownList(\common\models\Interview::$prenatalText,['prompt'=>'请选择']) ?>

    <?php // echo $form->field($model, 'childbirth_hospital') ?>



    <?php // echo $form->field($model, 'childbirth_date') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php  echo $form->field($model, 'pt_value')->dropDownList(\common\models\Interview::$prenatalValueText,['prompt'=>'请选择']) ?>

    <?php // echo $form->field($model, 'week') ?>
    <?= $form->field($model, 'field90')->widget(\kartik\select2\Select2::classname(), [
        'data' => \common\models\Pregnancy::$field90,
        'language' => 'de',

        'options' => ['placeholder' => '请选择','multiple'=>'multiple'],
        'pluginOptions' => [
            'allowClear' => true,
            'width'=>400,

        ],
    ])?>


    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
