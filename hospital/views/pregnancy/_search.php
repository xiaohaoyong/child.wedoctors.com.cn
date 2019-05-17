<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\PregnancySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pregnancy-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>
    <?= $form->field($model, 'field1') ?>

    <?= $form->field($model, 'field2')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'field11')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'level')->dropDownList([1=>'已签约'], ['prompt'=>'请选择']) ?>
    <?= $form->field($model, 'contract1')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'contract2')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]]) ?>

    <?php  //echo $form->field($model, 'field49')->dropDownList(\common\models\Pregnancy::$field49) ?>

    <?php // echo $form->field($model, 'field4') ?>

    <?php // echo $form->field($model, 'field5') ?>

    <?php // echo $form->field($model, 'field6') ?>

    <?php // echo $form->field($model, 'field7') ?>

    <?php // echo $form->field($model, 'field8') ?>

    <?php // echo $form->field($model, 'field9') ?>

    <?php // echo $form->field($model, 'field10') ?>

    <?php // echo $form->field($model, 'field11') ?>

    <?php // echo $form->field($model, 'field12') ?>

    <?php // echo $form->field($model, 'field13') ?>

    <?php // echo $form->field($model, 'field14') ?>

    <?php // echo $form->field($model, 'field15') ?>

    <?php // echo $form->field($model, 'field16') ?>

    <?php // echo $form->field($model, 'field17') ?>

    <?php // echo $form->field($model, 'field18') ?>

    <?php // echo $form->field($model, 'field19') ?>

    <?php // echo $form->field($model, 'field20') ?>

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

    <?php // echo $form->field($model, 'field31') ?>

    <?php // echo $form->field($model, 'field32') ?>

    <?php // echo $form->field($model, 'field33') ?>

    <?php // echo $form->field($model, 'field34') ?>

    <?php // echo $form->field($model, 'field35') ?>

    <?php // echo $form->field($model, 'field36') ?>

    <?php // echo $form->field($model, 'field37') ?>

    <?php // echo $form->field($model, 'field38') ?>

    <?php // echo $form->field($model, 'field39') ?>

    <?php // echo $form->field($model, 'field40') ?>

    <?php // echo $form->field($model, 'field41') ?>

    <?php // echo $form->field($model, 'field42') ?>

    <?php // echo $form->field($model, 'field43') ?>

    <?php // echo $form->field($model, 'field44') ?>

    <?php // echo $form->field($model, 'field45') ?>

    <?php // echo $form->field($model, 'field46') ?>

    <?php // echo $form->field($model, 'field47') ?>

    <?php // echo $form->field($model, 'field48') ?>

    <?php // echo $form->field($model, 'field49') ?>

    <?php // echo $form->field($model, 'field50') ?>

    <?php // echo $form->field($model, 'field51') ?>

    <?php // echo $form->field($model, 'field52') ?>

    <?php // echo $form->field($model, 'field53') ?>

    <?php // echo $form->field($model, 'field54') ?>

    <?php // echo $form->field($model, 'field55') ?>

    <?php // echo $form->field($model, 'field56') ?>

    <?php // echo $form->field($model, 'field57') ?>

    <?php // echo $form->field($model, 'field58') ?>

    <?php // echo $form->field($model, 'field59') ?>

    <?php // echo $form->field($model, 'field60') ?>

    <?php // echo $form->field($model, 'field61') ?>

    <?php // echo $form->field($model, 'field62') ?>

    <?php // echo $form->field($model, 'field63') ?>

    <?php // echo $form->field($model, 'field64') ?>

    <?php // echo $form->field($model, 'field65') ?>

    <?php // echo $form->field($model, 'field66') ?>

    <?php // echo $form->field($model, 'field67') ?>

    <?php // echo $form->field($model, 'field68') ?>

    <?php // echo $form->field($model, 'field70') ?>

    <?php // echo $form->field($model, 'field71') ?>

    <?php // echo $form->field($model, 'field72') ?>

    <?php // echo $form->field($model, 'field73') ?>

    <?php // echo $form->field($model, 'field74') ?>

    <?php // echo $form->field($model, 'field75') ?>

    <?php // echo $form->field($model, 'field76') ?>

    <?php // echo $form->field($model, 'field77') ?>

    <?php // echo $form->field($model, 'field78') ?>

    <?php // echo $form->field($model, 'field79') ?>

    <?php // echo $form->field($model, 'field80') ?>

    <?php // echo $form->field($model, 'field81') ?>

    <?php // echo $form->field($model, 'field82') ?>

    <?php // echo $form->field($model, 'field83') ?>

    <?php // echo $form->field($model, 'field84') ?>

    <?php // echo $form->field($model, 'field85') ?>

    <?php // echo $form->field($model, 'field86') ?>

    <?php // echo $form->field($model, 'field87') ?>

    <?php // echo $form->field($model, 'field88') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'isupdate') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
