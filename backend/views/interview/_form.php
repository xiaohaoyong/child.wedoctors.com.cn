<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Interview */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="interview-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'prenatal_test')->textInput() ?>

    <?= $form->field($model, 'pt_hospital')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pt_date')->textInput() ?>

    <?= $form->field($model, 'prenatal')->textInput() ?>

    <?= $form->field($model, 'childbirth_hospital')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'childbirth_date')->textInput() ?>

    <?= $form->field($model, 'createtime')->textInput() ?>

    <?= $form->field($model, 'userid')->textInput() ?>

    <?= $form->field($model, 'pt_value')->textInput() ?>

    <?= $form->field($model, 'week')->textInput() ?>

    <?= $form->field($model, 'sex')->textInput() ?>

    <?= $form->field($model, 'childbirth_type')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
