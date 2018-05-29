<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DataUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([1=>'政府',2=>'地区',3=>'医院']) ?>


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

    <?= $form->field($model,'county')->dropDownList($county,[
        'prompt'=>'请选择',
        'onchange'=>'
            $("#'.Html::getInputId($model,'hospital').'").html(\''.Html::tag('option',Html::encode("请选择"),array('value'=>0)).'\');
            $.post("'.\yii\helpers\Url::to(['hospital/get']).'?HospitalSearchModel[county]="+$(this).val(),function(data){
                $("#'.Html::getInputId($model,'hospital').'").html(data);
            });',
    ]) ?>

    <?= $form->field($model,'hospital')->dropDownList(\common\models\Hospital::find()->select('name')->indexBy('id')->where(['county'=>$model['county']])->column(), ['prompt'=>'请选择'])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
