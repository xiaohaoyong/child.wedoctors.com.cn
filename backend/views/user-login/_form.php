<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserLogin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-login-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'userid')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'phone')->textInput() ?>

    <?= $form->field($model, 'createtime')->textInput() ?>
                <?= $form->field($model, 'openid')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'xopenid')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'unionid')->textInput(['maxlength' => true]) ?>


                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
