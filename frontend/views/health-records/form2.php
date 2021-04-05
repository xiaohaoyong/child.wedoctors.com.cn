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
        <h2>家庭医生服务签约</h2>
        <div class="info">
            家长您好！
            <p>
                儿童健康关乎社会未来，每个人是自己健康第一责任人。从小养成健康生活方式和习惯，对于维护健康和疾病预防具有重要意义。
            </p>
            <p>
                为了更好提供新冠等传染病以及儿童常见病预防服务，做好在校学龄儿童健康管理等学校卫生服务，我校联合朝阳区八里庄社区卫生服务中心开展“家庭医生进校园”签约服务。
            </p>
            <p>
                疫情常态化防控期间，让我们共同做好学校卫生安全保障、做好学生健康维护工作，感谢各位家长的支持配合！
            </p>
            <p>
                请您仔细填写、核对以下信息，孩子个人信息我们将妥善保管：
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

    <div style="margin-left: 20px; margin-top: 10px;">同意签约点击提交</div>
    <div class="form-group button">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
            'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>
</div>
