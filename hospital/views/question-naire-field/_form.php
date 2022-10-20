<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\QuestionNaireField */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-naire-field-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'qnid')->textInput() ?>

    <?= $form->field($model, 'userid')->textInput() ?>

    <?= $form->field($model, 'createtime')->textInput() ?>

    <?= $form->field($model, 'sign')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doctorid')->textInput() ?>

    <?= $form->field($model, 'state')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
