<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\AppointSearchModels */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>
    <?php $county = \common\models\Area::$city[11] ? \common\models\Area::$county[11] : []; ?>

    <?= $form->field($model, 'county')->dropDownList($county, [
        'prompt' => '请选择',
        'onchange' => '
            $("#' . Html::getInputId($model, 'doctorid') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $.post("' . \yii\helpers\Url::to(['user-doctor/get']) . '?UserDoctorSearchModel[county]="+$(this).val(),function(data){
                $("#' . Html::getInputId($model, 'doctorid') . '").html(data);
            });',
    ]) ?>

    <?= $form->field($model, 'doctorid')->dropDownList(\common\models\UserDoctor::find()->select('name')->indexBy('userid')->where(['county' => $model['county']])->column(), ['prompt' => '请选择']) ?>

    <?php  echo $form->field($model, 'userid') ?>

    <?= $form->field($model, 'appoint_dates')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]])?>

    <?php  echo $form->field($model, 'appoint_time')->dropDownList(\common\models\Appoint::$timeText,['prompt'=>'请选择']) ?>

    <?php  echo $form->field($model, 'state')->dropDownList(\common\models\Appoint::$stateText,['prompt'=>'请选择']) ?>
    <?php  echo $form->field($model, 'type')->dropDownList(\common\models\Appoint::$typeText,['prompt'=>'请选择']) ?>

    <?php  echo $form->field($model, 'child_name') ?>

    <?php  echo $form->field($model, 'phone') ?>
    <?php  echo $form->field($model, 'userid') ?>

    <?php  echo $form->field($model, 'ids') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
