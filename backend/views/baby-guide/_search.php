<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BabyGuideSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="baby-guide-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>


    <?= $form->field($model,'title')->dropDownList(\common\models\BabyGuide::find()->select('title')->indexBy('title')->groupBy('title')->column(), ['prompt'=>'请选择'])?>


    <?= $form->field($model, 'period')->dropDownList(\common\models\BabyToolTag::find()->select('name')->indexBy('name')->groupBy('name')->column(), ['prompt'=>'请选择'])?>

    <?php // echo $form->field($model, 'tag') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
