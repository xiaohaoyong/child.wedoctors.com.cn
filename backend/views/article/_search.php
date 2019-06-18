<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>
    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'subject_pid')->dropDownList(\common\models\ArticleCategory::find()->select('name')->indexBy('id')->where(['pid'=>0])->column(), [
        'prompt'=>'请选择',
        'onchange'=>'
            $("#'.Html::getInputId($model,'subject').'").html(\''.Html::tag('option',Html::encode("请选择"),array('value'=>0)).'\');
            $.post("'.\yii\helpers\Url::to(['article-category/get']).'?ArticleCategorySearch[pid]="+$(this).val(),function(data){
                $("#'.Html::getInputId($model,'subject').'").html(data);
            });',
    ]) ?>
    <?= $form->field($model,'subject')->dropDownList(\common\models\ArticleCategory::find()->select('name')->indexBy('id')->where(['pid'=>$model->subject_pid])->column(), ['prompt'=>'请选择'])?>

    <?= $form->field($model, 'level')->dropDownList(\common\models\Article::$levelText,['prompt'=>'请选择']) ?>

    <?= $form->field($model, 'child_type')->dropDownList(\common\models\Article::$childText,['prompt'=>'请选择']) ?>

    <?php // echo $form->field($model, 'num') ?>

    <?php // echo $form->field($model, 'type') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
