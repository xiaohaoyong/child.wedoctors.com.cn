<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AppointHpvSetting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-hpv-setting-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'week1')->textInput() ?>

    <?= $form->field($model, 'week2')->textInput() ?>

    <?= $form->field($model, 'week3')->textInput() ?>

    <?= $form->field($model, 'week4')->textInput() ?>

    <?= $form->field($model, 'week5')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
