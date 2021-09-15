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

    <div class="title">基本信息</div>

    <?= $form->field($model, 'field1')->radioList(HealthRecords::$field1Txt) ?>

    <?= $form->field($model, 'field2')->radioList(HealthRecords::$field2Txt) ?>

    <?= $form->field($model, 'field3')->textInput(['maxlength' => true,'placeholder'=>'请填写家长姓名']) ?>

    <?= $form->field($model, 'field4')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field4')]) ?>

    <?= $form->field($model, 'field5')->radioList(HealthRecords::$field5Txt) ?>


    <?= $form->field($model, 'field6')->textInput(['maxlength' => true,'placeholder'=>'请填写'.$model->getAttributeLabel('field6')]) ?>

    <?= $form->field($model, 'field7')->radioList(HealthRecords::$field7Txt) ?>

    <?= $form->field($model, 'field8')->radioList(HealthRecords::$field8Txt) ?>


    <?= $form->field($model, 'field9')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field9')]) ?>

    <?= $form->field($model, 'field10')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field10')]) ?>

    <?= $form->field($model, 'field11')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field11')]) ?>

    <?= $form->field($model, 'field12')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field12')]) ?>

    <?= $form->field($model, 'field13')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field13')]) ?>

    <?= $form->field($model, 'field14')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field14')]) ?>

    <?= $form->field($model, 'field15')->textInput(['maxlength' => true,['placeholder'=>'请填写'.$model->getAttributeLabel('field15')]]) ?>
    <div class="title">病史情况</div>

    <?= $form->field($model, 'field16')->radioList(HealthRecords::$field16Txt) ?>

    <?= $form->field($model, 'field16_text')->textInput(['maxlength' => true,['placeholder'=>'请填写'.$model->getAttributeLabel('field16_text')]]) ?>

    <?= $form->field($model, 'field17')->radioList(HealthRecords::$field17Txt) ?>

    <?= $form->field($model, 'field17_text')->textInput(['maxlength' => true,['placeholder'=>'请填写'.$model->getAttributeLabel('field17_text')]]) ?>

    <?= $form->field($model, 'field18')->radioList(HealthRecords::$field18Txt) ?>

    <?= $form->field($model, 'field18_text')->textInput(['maxlength' => true,['placeholder'=>'请填写'.$model->getAttributeLabel('field18_text')]]) ?>

    <?= $form->field($model, 'field19')->radioList(HealthRecords::$field19Txt) ?>

    <?= $form->field($model, 'field19_text')->textInput(['maxlength' => true,['placeholder'=>'请填写'.$model->getAttributeLabel('field19_text')]]) ?>

    <?= $form->field($model, 'field20')->radioList(HealthRecords::$field20Txt) ?>

    <?= $form->field($model, 'field20_text')->textInput(['maxlength' => true,['placeholder'=>'请填写'.$model->getAttributeLabel('field20_text')]]) ?>
    <div class="title">生活习惯</div>

    <?= $form->field($model, 'field21')->radioList(HealthRecords::$field21Txt) ?>

    <?= $form->field($model, 'field22')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field22')]) ?>

    <?= $form->field($model, 'field23')->radioList(HealthRecords::$field23Txt) ?>

    <?= $form->field($model, 'field24')->radioList(HealthRecords::$field24Txt) ?>

    <?= $form->field($model, 'field25')->radioList(HealthRecords::$field25Txt) ?>

    <?= $form->field($model, 'field26')->radioList(HealthRecords::$field26Txt) ?>

    <?= $form->field($model, 'field27')->radioList(HealthRecords::$field27Txt) ?>

    <?= $form->field($model, 'field28')->textInput(['placeholder'=>'请填写'.$model->getAttributeLabel('field28')]) ?>
    <div class="form-group button">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
            'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
