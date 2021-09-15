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

                <div class="col-lg-3 control-label">
                    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
