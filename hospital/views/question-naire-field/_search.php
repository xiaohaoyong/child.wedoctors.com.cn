<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\QuestionNaireFieldSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-naire-field-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'qnid') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'createtime') ?>

    <?= $form->field($model, 'sign') ?>

    <?php // echo $form->field($model, 'doctorid') ?>

    <?php // echo $form->field($model, 'state') ?>

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
            jQuery("#w0").attr('action',"/question-naire-field/down");    //通
            jQuery("#w0").submit();    //提交ID为myform的表单
    });
JS;
$this->registerJs($updateJs);
?>
