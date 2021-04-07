<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\HealthRecordsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="health-records-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'field1') ?>

    <?= $form->field($model, 'field2') ?>

    <?= $form->field($model, 'field3') ?>

    <?= $form->field($model, 'field4') ?>

    <?php // echo $form->field($model, 'field5') ?>

    <?php // echo $form->field($model, 'field5_text') ?>

    <?php // echo $form->field($model, 'field6') ?>

    <?php // echo $form->field($model, 'field7') ?>

    <?php // echo $form->field($model, 'field8') ?>

    <?php // echo $form->field($model, 'field8_text') ?>

    <?php // echo $form->field($model, 'field9') ?>

    <?php // echo $form->field($model, 'field10') ?>

    <?php // echo $form->field($model, 'field11') ?>

    <?php // echo $form->field($model, 'field12') ?>

    <?php // echo $form->field($model, 'field13') ?>

    <?php // echo $form->field($model, 'field14') ?>

    <?php // echo $form->field($model, 'field15') ?>

    <?php // echo $form->field($model, 'field41') ?>

    <?php // echo $form->field($model, 'field42') ?>

    <?php // echo $form->field($model, 'field16') ?>

    <?php // echo $form->field($model, 'field16_text') ?>

    <?php // echo $form->field($model, 'field17') ?>

    <?php // echo $form->field($model, 'field17_text') ?>

    <?php // echo $form->field($model, 'field18') ?>

    <?php // echo $form->field($model, 'field18_text') ?>

    <?php // echo $form->field($model, 'field19') ?>

    <?php // echo $form->field($model, 'field19_text') ?>

    <?php // echo $form->field($model, 'field20') ?>

    <?php // echo $form->field($model, 'field20_text') ?>

    <?php // echo $form->field($model, 'field21') ?>

    <?php // echo $form->field($model, 'field22') ?>

    <?php // echo $form->field($model, 'field23') ?>

    <?php // echo $form->field($model, 'field24') ?>

    <?php // echo $form->field($model, 'field25') ?>

    <?php // echo $form->field($model, 'field26') ?>

    <?php // echo $form->field($model, 'field27') ?>

    <?php // echo $form->field($model, 'field28') ?>

    <?php // echo $form->field($model, 'field29') ?>

    <?php // echo $form->field($model, 'field30') ?>

    <?php // echo $form->field($model, 'field40') ?>

    <?php // echo $form->field($model, 'field34') ?>

    <?php // echo $form->field($model, 'field31') ?>

    <?php // echo $form->field($model, 'field32') ?>

    <?php // echo $form->field($model, 'field33') ?>

    <?php // echo $form->field($model, 'field39') ?>

    <?php // echo $form->field($model, 'field35') ?>

    <?php // echo $form->field($model, 'field36') ?>

    <?php // echo $form->field($model, 'field37') ?>

    <?php // echo $form->field($model, 'field38') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'doctorid') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php // echo $form->field($model, 'field43') ?>

    <?php // echo $form->field($model, 'field44') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
