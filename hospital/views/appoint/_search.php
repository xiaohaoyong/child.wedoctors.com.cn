<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\AppointSearchModels */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'appoint_dates')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]])?>

    <?php  echo $form->field($model, 'appoint_time')->dropDownList(\common\models\Appoint::$timeText,['prompt'=>'请选择']) ?>

    <?php  echo $form->field($model, 'state')->dropDownList(\common\models\Appoint::$stateText,['prompt'=>'请选择']) ?>

    <?php  echo $form->field($model, 'child_name') ?>

    <?php  echo $form->field($model, 'phone') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
