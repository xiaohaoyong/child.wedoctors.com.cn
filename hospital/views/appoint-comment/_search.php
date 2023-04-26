<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Hospital\models\AppointCommentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

   <!-- 先注释下$form->field($model, 'aid')  $form->field($model, 'userid') -->


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
    <?=$form->field($model,'is_rate')->dropDownList(\common\models\AppointComment::$allArr,['prompt'=>'全部']);?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
