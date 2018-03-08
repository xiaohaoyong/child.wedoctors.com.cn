<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model databackend\models\user\ChildInfoSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="child-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model->doctorParent, 'level')->dropdownList(\common\models\DoctorParent::$levelText,['prompt'=>'请选择']) ?>
    <?= $form->field($model->doctorParent, 'createtime')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]])?>
    <?= $form->field($model->userParent, 'field11')?>
    <?= $form->field($model->userParent, 'field12')?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
