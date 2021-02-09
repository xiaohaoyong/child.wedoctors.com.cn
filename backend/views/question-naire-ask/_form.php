<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\QuestionNaireAsk */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-naire-ask-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'type')->dropDownList(\common\models\QuestionNaireAsk::$typeText,['prompt'=>'请选择']) ?>

    <?= $form->field($model, 'qnid')->dropDownList(\common\models\QuestionNaire::find()->select('title')->indexBy('id')->column(),['prompt'=>'请选择']) ?>
                <?= $form->field($model, 'field')->dropDownList(\common\models\QuestionNaireAsk::$fieldText) ?>
                <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
