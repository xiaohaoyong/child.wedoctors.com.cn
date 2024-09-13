<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AppointExpert */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-expert-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            <?php
                $doctor=\common\models\UserDoctor::findOne(['hospitalid'=>Yii::$app->user->identity->hospitalid]);
                ?>
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'info')->textarea(['rows' => 5]) ?>

    <?= $form->field($model, 'doctorid')->hiddenInput(['value'=>$doctor->userid])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
