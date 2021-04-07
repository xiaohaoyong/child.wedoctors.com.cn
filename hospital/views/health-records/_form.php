<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HealthRecords */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="health-records-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'field1')->textInput() ?>

    <?= $form->field($model, 'field3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field6')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field7')->textInput() ?>

    <?= $form->field($model, 'field8')->textInput() ?>

    <?= $form->field($model, 'field9')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field10')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field11')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field12')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field13')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field14')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field15')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field42')->textInput() ?>

    <?= $form->field($model, 'field16')->textInput() ?>

    <?= $form->field($model, 'field16_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field17')->textInput() ?>

    <?= $form->field($model, 'field17_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field18')->textInput() ?>

    <?= $form->field($model, 'field18_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field19')->textInput() ?>

    <?= $form->field($model, 'field19_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field20')->textInput() ?>

    <?= $form->field($model, 'field20_text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field21')->textInput() ?>

    <?= $form->field($model, 'field22')->textInput() ?>

    <?= $form->field($model, 'field23')->textInput() ?>

    <?= $form->field($model, 'field24')->textInput() ?>

    <?= $form->field($model, 'field25')->textInput() ?>

    <?= $form->field($model, 'field26')->textInput() ?>

    <?= $form->field($model, 'field27')->textInput() ?>

    <?= $form->field($model, 'field28')->textInput() ?>

    <?= $form->field($model, 'field29')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'field30')->textInput() ?>

    <?= $form->field($model, 'field40')->textInput() ?>

    <?= $form->field($model, 'field34')->textInput(['maxlength' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
