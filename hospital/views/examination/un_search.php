<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\user\ChildInfoSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="child-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['undone'],
        'method' => 'get',
        'options' => ['id'=>'child','class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'child_type')->dropDownList([
            1=>'3月龄',
        2=>'5-6月龄',
        3=>'8-9月龄',
        4=>'12月龄',
        5=>'18月龄',
        6=>'24月龄',
        7=>'2岁6月龄',
        8=>'3岁',
        9=>'3岁6月龄',
        10=>'4岁'], ['prompt' => '请选择']) ?>
    <?= $form->field($model, 'level')->dropdownList([0=>'全部',1=>'已签约'], ['prompt' => '请选择']) ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>