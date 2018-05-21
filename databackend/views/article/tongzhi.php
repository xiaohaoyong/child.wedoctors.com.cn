<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $article common\models\ArticleInfo */
/* @var $form yii\widgets\ActiveForm */
$this->title = '通知';

?>

<div class="article-form">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($article, 'title')->textInput()->label('通知标题') ?>
                <?= $form->field($article, 'ftitle')->textInput()->label('通知副标题') ?>



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
