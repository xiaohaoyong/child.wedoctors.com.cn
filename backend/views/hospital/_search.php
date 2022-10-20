<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HospitalSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospital-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>


    <?= $form->field($model, 'name') ?>
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
    <?php $city= $model->province &&\common\models\Area::$city[$model->province]?\common\models\Area::$city[$model->province]:[];?>
    <?= $form->field($model,'city')->dropDownList($city,
        [
            'prompt'=>'请选择',
            'onchange'=>'
            $("#'.Html::getInputId($model,'county').'").html(\''.Html::tag('option',Html::encode("请选择"),array('value'=>0)).'\');
            $.post("'.\yii\helpers\Url::to(['area/get']).'?type=county&id="+$(this).val(),function(data){
                $("#'.Html::getInputId($model,'county').'").html(data);
            });',
        ]) ?>
    <?php $county=$model->city &&\common\models\Area::$city[$model->city]?\common\models\Area::$county[$model->city]:[];?>

    <?= $form->field($model,'county')->dropDownList($county,['prompt'=>'请选择']) ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'rank') ?>

    <?php // echo $form->field($model, 'nature') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
