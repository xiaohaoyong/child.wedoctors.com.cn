<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HospitalForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospital-form-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'sign1')->textInput() ?>

    <?= $form->field($model, 'sign2')->textInput() ?>

    <?= $form->field($model, 'sign3')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'ratio1')->textInput() ?>

    <?= $form->field($model, 'ratio2')->textInput() ?>

    <?= $form->field($model, 'appoint_num')->textInput() ?>

    <?= $form->field($model, 'other_appoint_num')->textInput() ?>

    <?= $form->field($model, 'doctorid')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
