<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AppointCommentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'aid') ?>

    <?= $form->field($model, 'userid') ?>
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


    <?= $form->field($model, 'startDate')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'endDate')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>

    <?=$form->field($model,'is_envir')->dropDownList(\common\models\AppointComment::$enverstaffArr,['prompt'=>'全部']);?>
    <?=$form->field($model,'is_process')->dropDownList(\common\models\AppointComment::$enverstaffArr,['prompt'=>'全部']);?>
    <?=$form->field($model,'is_staff')->dropDownList(\common\models\AppointComment::$enverstaffArr,['prompt'=>'全部']);?>


    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
