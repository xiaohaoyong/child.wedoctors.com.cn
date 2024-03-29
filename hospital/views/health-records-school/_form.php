<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HealthRecordsSchool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="health-records-school-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'doctor_name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'doctor_phone')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'family_name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'nianji')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'school_name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'school_phone')->textInput(['maxlength' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
