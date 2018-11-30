<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Doctors */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctors-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'template' => '<div class="col-lg-3 control-label color666 fontweight">{label}:</div>
                                                    <div class="col-lg-5" style="padding-left: 15px;padding-right: 15px;">{input}</div>
                                                    <div class="col-lg-4">{error}</div>',
                        'inputOptions' => ['class' => 'form-control'],
                    ],
                    'options' => ['class' => 'form-horizontal t-margin20', 'id' => 'form1', 'enctype' => "multipart/form-data"],
                ]); ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'type')->checkboxList(\common\models\Doctors::$typeText) ?>


                <?= $form->field($model, 'province')->dropDownList(\common\models\Area::$province,
                    [
                        'prompt' => '请选择',
                        'onchange' => '
            $("#' . Html::getInputId($model, 'city') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $("#' . Html::getInputId($model, 'county') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $.post("' . \yii\helpers\Url::to(['area/get']) . '?id="+$(this).val(),function(data){
                $("#' . Html::getInputId($model, 'city') . '").html(data);
            });',
                    ]) ?>
                <?php $city = \common\models\Area::$city[$model->province] ? \common\models\Area::$city[$model->province] : []; ?>
                <?= $form->field($model, 'city')->dropDownList($city,
                    [
                        'prompt' => '请选择',
                        'onchange' => '
            $("#' . Html::getInputId($model, 'county') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $.post("' . \yii\helpers\Url::to(['area/get']) . '?type=county&id="+$(this).val(),function(data){
                $("#' . Html::getInputId($model, 'county') . '").html(data);
            });',
                    ]) ?>
                <?php $county = \common\models\Area::$city[$model->province] ? \common\models\Area::$county[$model->city] : []; ?>

                <?= $form->field($model, 'county')->dropDownList($county, [
                    'prompt' => '请选择',
                    'onchange' => '
            $("#' . Html::getInputId($model, 'hospitalid') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $.post("' . \yii\helpers\Url::to(['hospital/get']) . '?HospitalSearchModel[county]="+$(this).val(),function(data){
                $("#' . Html::getInputId($model, 'hospitalid') . '").html(data);
            });',
                ]) ?>

                <?= $form->field($model, 'hospitalid')->dropDownList(\common\models\Hospital::find()->select('name')->indexBy('id')->where(['county' => $model['county']])->column(), ['prompt' => '请选择']) ?>


                <div class="col-lg-3 control-label">
                    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
