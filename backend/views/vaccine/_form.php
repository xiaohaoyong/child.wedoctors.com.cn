<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Vaccine */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vaccine-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'disease')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'adverseReactions')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'contraindications')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'diseaseHarm')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'dealFlow')->textarea(['rows' => 6]) ?>


    <?= $form->field($model, 'intervalName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'source')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
