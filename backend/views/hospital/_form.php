<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\hospital\Hospital */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospital-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model,'province')->dropDownList(\common\models\Area::$province,
        [
            'prompt'=>'请选择',
            'onchange'=>'
            $("#'.Html::getInputId($model,'city').'").html(\''.Html::tag('option',Html::encode("请选择"),array('value'=>0)).'\');
            $("#'.Html::getInputId($model,'county').'").html(\''.Html::tag('option',Html::encode("请选择"),array('value'=>0)).'\');
            $.post("'.\yii\helpers\Url::to(['area/get']).'?id="+$(this).val(),function(data){
                $("#'.Html::getInputId($model,'city').'").html(data);
            });',
        ]) ?>
    <?php $city=\common\models\Area::$city[$model->province]?\common\models\Area::$city[$model->province]:[];?>
    <?= $form->field($model,'city')->dropDownList($city,
        [
            'prompt'=>'请选择',
            'onchange'=>'
            $("#'.Html::getInputId($model,'county').'").html(\''.Html::tag('option',Html::encode("请选择"),array('value'=>0)).'\');
            $.post("'.\yii\helpers\Url::to(['area/get']).'?type=county&id="+$(this).val(),function(data){
                $("#'.Html::getInputId($model,'county').'").html(data);
            });',
        ]) ?>
    <?php $county=\common\models\Area::$city[$model->province]?\common\models\Area::$county[$model->city]:[];?>

    <?= $form->field($model,'county')->dropDownList($county,['prompt'=>'请选择']) ?>

    <?= $form->field($model, 'type')->dropDownList(\common\models\Hospital::$typeText, ['prompt'=>'请选择']) ?>

    <?= $form->field($model, 'rank')->dropDownList(\common\models\Hospital::$rankText, ['prompt'=>'请选择']) ?>

    <?= $form->field($model, 'nature')->dropDownList(\common\models\Hospital::$natureText, ['prompt'=>'请选择']) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
