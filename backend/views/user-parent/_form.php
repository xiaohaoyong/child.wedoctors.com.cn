<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserParent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-parent-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'userid')->textInput() ?>
                <?= $form->field($model, 'mother')->textInput() ?>
                <?= $form->field($model, 'mother_id')->textInput() ?>
                <?= $form->field($model, 'fieldu46')->textInput() ?>
                <?= $form->field($model, 'fieldp47')->textInput() ?>
                <?= $form->field($model, 'fieldp47')->textInput() ?>
                <?= $form->field($model, 'field28')->textInput() ?>
                <?= $form->field($model, 'mother_phone')->textInput() ?>

                
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
