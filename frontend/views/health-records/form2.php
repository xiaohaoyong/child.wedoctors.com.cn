<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\HealthRecords;

/* @var $this yii\web\View */
/* @var $model common\models\HealthRecords */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    body{background: #ffffff;}
</style>
<div class="health-records">
    <div class="header">
        <div><img src="/img/health-records-header.png" width="190px"></div>
        <div class="info">
            家长您好！
            <p>
            为了更好开展学生在校期间健康管理，请您
            配合填写学生健康相关信息，本表信息不会外泄，
            请您如实填写，谢谢您的配合。
            </p>
        </div>

    </div>
    <?php $form = ActiveForm::begin([
            'options'=>[
                'class'=>'form-horizontal',
            ],
        'fieldConfig' => [  //统一修改字段的模板
            'template' => "<div class='form-group-info'>{label}\n<div class='form-input'>{input}</div></div>{error}",
            'inputOptions'=>['class'=>''],
        ],
    ]);
    ?>
    <div class="title">个人信息</div>
    <?= $form->field($model, 'field29')->textInput(['maxlength' => true,'placeholder'=>'请填写学生姓名']) ?>

    <?= $form->field($model, 'field30')->dropDownList(\common\models\HealthRecordsSchool::find()->select('name')->indexBy('id')->where(['doctorid'=>$doctorid])->column(),['prompt'=>'请选择']) ?>
    <?= $form->field($model, 'field34')->textInput(['maxlength' => true,'placeholder'=>'请填写学生年级']) ?>
    <?= $form->field($model, 'field40')->textInput(['maxlength' => true,'placeholder'=>'请填写学生班级']) ?>
    <?= $form->field($model, 'field41')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field41')])?>

    <?= $form->field($model, 'field42')->dropDownList(HealthRecords::$field41Txt,['prompt'=>'请选择'])?>
    <?= $form->field($model, 'field3')->textInput(['maxlength' => true,'placeholder'=>'请填写家长姓名']) ?>

    <?= $form->field($model, 'field4')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field4')]) ?>
    <?= $form->field($model, 'field6')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field6')]) ?>
    <div class="form-group button">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
            'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
