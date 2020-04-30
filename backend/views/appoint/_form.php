<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Appoint */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-form">
    <div class="col-xs-12">
        <div class="box">
            <a href="http://web.child.wedoctors.com.cn/1587887200.html" target="_blank">详情</a>
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'userid')->textInput() ?>

    <?= $form->field($model, 'doctorid')->textInput() ?>

    <?= $form->field($model, 'createtime')->textInput() ?>

    <?= $form->field($model, 'appoint_time')->textInput() ?>

    <?= $form->field($model, 'appoint_date')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'childid')->textInput() ?>

    <?= $form->field($model, 'phone')->textInput() ?>
                <?= $form->field($model, 'state')->textInput() ?>
                <?= $form->field($model, 'remark')->textInput() ?>
                <?= $form->field($model, 'cancel_type')->textInput() ?>
                <?= $form->field($model, 'push_state')->textInput() ?>
                <?= $form->field($model, 'mode')->textInput() ?>
                <?= $form->field($model, 'vaccine')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交': '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
