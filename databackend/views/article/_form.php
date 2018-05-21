<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $article common\models\ArticleInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($article, 'title')->textInput() ?>

                <?= $form->field($model,'catid')->radioList(\common\models\Article::$catText)?>

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
                if(\Yii::$app->user->identity->type != 1){
                    unset($typeText[1]);
                }?>
                <?= $form->field($model,'type')->radioList($typeText)?>

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
            <!-- /.box-body -->
        </div>
    </div>
</div>
