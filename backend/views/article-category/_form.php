<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-category-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'pid')->dropDownList(\common\models\ArticleCategory::find()->select('name')->indexBy('id')->where(['pid' => 0])->column(), ['prompt' => '请选择']) ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
                <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>

