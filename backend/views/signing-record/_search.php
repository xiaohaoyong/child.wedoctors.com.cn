<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$hdata = \common\models\Hospital::find()->orderBy('id')->all();


/* @var $this yii\web\View */
/* @var $model app\models\SigningRecordSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="signing-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'name') ?>

    &nbsp;&nbsp;

    <?= $form->field($model, 'type')->dropDownList(['1'=>'孕妈','2'=>'宝宝'],['prompt'=>'请选择']); ?>
    &nbsp;&nbsp;
    <?= $form->field($model, 'sign_item_id_from')->dropDownList(ArrayHelper::map($hdata,'id','name'),['prompt'=>'请选择']) ?>
    &nbsp;&nbsp;
    <?= $form->field($model, 'sign_item_id_to')->dropDownList(ArrayHelper::map($hdata,'id','name'),['prompt'=>'请选择']) ?>

    <br/>

    <?= $form->field($model, 'startDate')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>
    &nbsp;&nbsp;
    <?= $form->field($model, 'endDate')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>

    <br/>

    <?php $county = \common\models\Area::$city[11] ? \common\models\Area::$county[11] : []; ?>
    <?= $form->field($model, 'county')->dropDownList($county, [
        'prompt' => '请选择',
        'onchange' => '
            $("#' . Html::getInputId($model, 'doctorid') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $.post("' . \yii\helpers\Url::to(['user-doctor/get']) . '?UserDoctorSearchModel[county]="+$(this).val(),function(data){
                $("#' . Html::getInputId($model, 'operator') . '").html(data);
            });',
    ]) ?>
    &nbsp;&nbsp;
    <?= $form->field($model, 'operator')->dropDownList(\common\models\UserDoctor::find()->select('name')->indexBy('userid')->where(['county' => $model['county']])->column(), ['prompt' => '请选择']) ?>




    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'info_pics') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
