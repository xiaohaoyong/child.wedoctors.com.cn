<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DataUpdateRecordSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-update-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?php // echo $form->field($model, 'new_num') ?>

    <?= $form->field($model, 'hospitalid')->dropdownList(\common\models\UserDoctor::find()->select('name')->indexBy('hospitalid')->column(),['prompt'=>'请选择']) ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
