<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $article common\models\ArticleInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($article, 'title')->textInput() ?>

    <?= $form->field($model, 'subject_pid')->dropDownList(\common\models\ArticleCategory::find()->select('name')->indexBy('id')->where(['pid'=>0])->column(), [
        'prompt'=>'请选择',
        'onchange'=>'
            $("#'.Html::getInputId($model,'subject').'").html(\''.Html::tag('option',Html::encode("请选择"),array('value'=>0)).'\');
            $.post("'.\yii\helpers\Url::to(['article-category/get']).'?ArticleCategorySearch[pid]="+$(this).val(),function(data){
                $("#'.Html::getInputId($model,'subject').'").html(data);
            });',
    ]) ?>
    <?= $form->field($model,'subject')->dropDownList(\common\models\ArticleCategory::find()->select('name')->indexBy('id')->where(['pid'=>$model->subject_pid])->column(), ['prompt'=>'请选择'])?>


    <?= $form->field($model,'child_type')->radioList(\common\models\Article::$childText)?>
    <?= $form->field($article, 'img')->fileInput() ?>
    <?php
    if($article->img){

        ?>
        <div class="field-articleInfo-img required">
            <label class="col-lg-3 control-label" for="articleInfo-img">封面</label>
            <?=Html::img($article->img,['width' => 300])?>
            <div class="help-block"></div>
        </div>
    <?php }?>

    <?php
    $typeText=\common\models\Article::$typeText;
    ?>
    <?= $form->field($model,'type')->radioList($typeText)?>
    <?= $form->field($model,'art_type')->radioList(\common\models\Article::$artTypeText)?>
    <?= $form->field($article,'video_url')->textInput()?>

    <?= $form->field($article, 'content')->widget(\yii\redactor\widgets\Redactor::className(), [
        'clientOptions' => [
            'lang' => 'zh_cn',
            'plugins' => ['clips', 'fontcolor','imagemanager']
        ]
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
