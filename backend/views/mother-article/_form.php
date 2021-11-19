<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MotherArticle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mother-article-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::className(), [
                    'clientOptions' => [
                        'lang' => 'zh_cn',
                        'plugins' => ['clips', 'fontcolor','imagemanager']
                    ]
                ]) ?>
                <?= $form->field($model, 'qcode')->fileInput() ?>

                <?php
                if($model->qcode){

                    ?>
                    <div class="field-articleInfo-img required">
                        <label class="col-lg-3 control-label" for="articleInfo-img">企业微信二维码</label>
                        <?=Html::img($model->qcode,['width' => 300])?>
                        <div class="help-block"></div>
                    </div>
                <?php }?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                    'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
