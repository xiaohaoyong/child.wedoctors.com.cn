<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HospitalMail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospital-mail-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>


                <?= $form->field($model, 'touser')->widget(\kartik\select2\Select2::classname(), [
                    'data' =>[0=>'全部']+\common\models\UserDoctor::find()->select('name')->indexBy('hospitalid')->column(),
                    'language' => 'de',
                    'options' => ['placeholder' => '请选择'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])?>
                <?= $form->field($model, 'touser')->dropdownList([0=>'全部']+\common\models\UserDoctor::find()->select('name')->indexBy('hospitalid')->column(),['prompt'=>'请选择']) ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
