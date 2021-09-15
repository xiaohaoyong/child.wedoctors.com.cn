<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\QuestionNaireFieldSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-naire-field-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>
    <?= $form->field($model, 'qnid')->hiddenInput(['value'=>$qnid])->label(false) ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'value') ?>

    <?= $form->field($model, 'createtime_e')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'createtime_s')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::button('下载', ['id' => 'down', 'class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$updateJs = <<<JS
jQuery("#down").click(function () {
        //过jquery为action属性赋值
            jQuery("#w0").attr('action',"/question-naire-field/down?qnid={$qnid}");    //通
            jQuery("#w0").submit();    //提交ID为myform的表单
    });
JS;
$this->registerJs($updateJs);
?>
