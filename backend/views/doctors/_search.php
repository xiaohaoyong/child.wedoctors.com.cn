<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DoctorsSearchModels */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctors-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'name') ?>


    <?php $county = \common\models\Area::$city[11] ? \common\models\Area::$county[11] : []; ?>

    <?= $form->field($model, 'county')->dropDownList($county, [
        'prompt' => '请选择',
        'onchange' => '
            $("#' . Html::getInputId($model, 'hospitalid') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $.post("' . \yii\helpers\Url::to(['user-doctor/geta']) . '?UserDoctorSearchModel[county]="+$(this).val(),function(data){
                $("#' . Html::getInputId($model, 'hospitalid') . '").html(data);
            });',
    ]) ?>


    <?= $form->field($model, 'hospitalid')->dropdownList(\common\models\UserDoctor::find()->select('name')->indexBy('hospitalid')->where(['county' => $model['county']])->column(),['prompt'=>'请选择']) ?>

    <?php // echo $form->field($model, 'subject_b') ?>

    <?php // echo $form->field($model, 'subject_s') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'intro') ?>

    <?php // echo $form->field($model, 'avatar') ?>

    <?php // echo $form->field($model, 'skilful') ?>

    <?php // echo $form->field($model, 'idnum') ?>

    <?php // echo $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'county') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'atitle') ?>

    <?php // echo $form->field($model, 'otype') ?>

    <?php // echo $form->field($model, 'authimg') ?>

    <?php // echo $form->field($model, 'type') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
