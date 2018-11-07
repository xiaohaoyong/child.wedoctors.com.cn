<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserDoctorAppoint */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-doctor-appoint-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'doctorid')->textInput() ?>

    <?= $form->field($model, 'cycle')->textInput() ?>

    <?= $form->field($model, 'delay')->textInput() ?>

    <?= $form->field($model, 'type1_num')->textInput() ?>

    <?= $form->field($model, 'type2_num')->textInput() ?>

    <?= $form->field($model, 'type3_num')->textInput() ?>

    <?= $form->field($model, 'type4_num')->textInput() ?>

    <?= $form->field($model, 'type5_num')->textInput() ?>

    <?= $form->field($model, 'type6_num')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
