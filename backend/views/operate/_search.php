<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\operate\OperateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'admin_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\rbac\AuthAdminuser::find()->asArray()->all(),'id','userlogin'),['prompt' => '全部']) ?>
    <?= $form->field($model, 'operate_time')->widget(\common\widgets\DatePicker::className(),[]) ?>

    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
