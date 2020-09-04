<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AppointCalling */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="login-box">

<div class="appoint-calling-form">
    <div class="login-logo">
        <a href="#">诊室设置</a>
    </div>
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->radioList(\common\models\Appoint::$typeText) ?>

                <div class="form-group">
                    注：开始接诊后一小时内容如没有操作将停止分配预约患者，操作包括（完成，提醒，恢复，跳过）
                </div>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '开始接诊'                    : '开始接诊', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
</div>