<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\user\ChildInfoSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="child-info-search">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => ['id'=>'child','class' => 'form-inline'],
        ]); ?>
        <?= $form->field($model, 'admin')->dropdownList(\common\models\UserDoctor::find()->select('name')->indexBy('hospitalid')->column(),['prompt'=>'请选择']) ?>

        <?= $form->field($model, 'child_type')->dropDownList(\common\models\Article::$childText, ['prompt' => '请选择']) ?>

        <?= $form->field($model, 'level')->dropdownList([1=>'已签约',2=>'已签约未关联',3=>'未签约',4=>'服务过已签约'],['prompt'=>'请选择']) ?>
        <?= $form->field($model, 'docpartimeS')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autocomplete'=>'off',
            'todayHighlight' => true
        ]])?>
        <?= $form->field($model, 'docpartimeE')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autocomplete'=>'off',
            'todayHighlight' => true
        ]])?>

        <?= $form->field($model, 'birthdayS')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autocomplete'=>'off',
            'todayHighlight' => true
        ]])?>
        <?= $form->field($model, 'birthdayE')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autocomplete'=>'off',
            'todayHighlight' => true
        ]])?>
        <?= $form->field($model, 'username')?>
        <?= $form->field($model, 'userphone')?>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
            <div class="help-block"></div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>