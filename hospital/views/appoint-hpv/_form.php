<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AppointHpv */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-hpv-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'phone')->textInput() ?>

                <?= $form->field($model, 'date')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autocomplete' => 'off',
                    'todayHighlight' => true
                ]]) ?>
                <?= $form->field($model, 'state')->dropDownList(\common\models\AppointHpv::$stateText, ['prompt' => '请选择']) ?>
                <?= $form->field($model, 'vid')->dropDownList(\common\models\Vaccine::find()->select('name')->indexBy('id')->column(), ['prompt' => '请选择']) ?>

                <?php
                if($model->img){

                    ?>
                    <div class="field-articleInfo-img required">
                        <a href="<?=$model->img?>" target="_blank">
                        <label class="col-lg-3 control-label" for="articleInfo-img">所属辖区的本人居委会证明</label>
                        <?=Html::img($model->img,['width' => 300])?>
                        <div class="help-block"></div>
                        </a>
                    </div>
                <?php }?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
