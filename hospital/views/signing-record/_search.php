<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$hdata = \common\models\UserDoctor::find()->orderBy('userid')->all();
$odata = \common\models\UserDoctor::find()->select('userid,name')->where([
        'in','userid',[
         47156,
        \Yii::$app->user->identity->doctorid
    ]
])->asArray()->all();


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
    &nbsp;&nbsp;&nbsp;&nbsp;
    <?= $form->field($model, 'sign_item_id_from')->dropDownList(ArrayHelper::map($hdata,'userid','name'),['prompt'=>'请选择']) ?>
    &nbsp;&nbsp;

    <br/>

    <?= $form->field($model, 'startDate')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?= $form->field($model, 'endDate')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>

    <br/>
    <?= $form->field($model, 'status')->dropDownList(['0'=>'未审核','1'=>'审核通过','2'=>'审核不通过'],['prompt'=>'请选择']); ?>

    <?= $form->field($model, 'operator')->dropDownList(ArrayHelper::map($odata,'userid','name'),['prompt'=>'请选择']) ?>

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
