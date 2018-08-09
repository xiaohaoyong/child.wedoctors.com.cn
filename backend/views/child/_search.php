<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ChildSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="child-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'birthday') ?>

    <?= $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'admin') ?>

    <?php // echo $form->field($model, 'field54') ?>

    <?php // echo $form->field($model, 'field53') ?>

    <?php // echo $form->field($model, 'field52') ?>

    <?php // echo $form->field($model, 'field51') ?>

    <?php // echo $form->field($model, 'field50') ?>

    <?php // echo $form->field($model, 'field49') ?>

    <?php // echo $form->field($model, 'field48') ?>

    <?php // echo $form->field($model, 'field47') ?>

    <?php // echo $form->field($model, 'field46') ?>

    <?php // echo $form->field($model, 'field45') ?>

    <?php // echo $form->field($model, 'field44') ?>

    <?php // echo $form->field($model, 'field43') ?>

    <?php // echo $form->field($model, 'field42') ?>

    <?php // echo $form->field($model, 'field41') ?>

    <?php // echo $form->field($model, 'field40') ?>

    <?php // echo $form->field($model, 'field39') ?>

    <?php // echo $form->field($model, 'field38') ?>

    <?php // echo $form->field($model, 'field37') ?>

    <?php // echo $form->field($model, 'field27') ?>

    <?php // echo $form->field($model, 'field26') ?>

    <?php // echo $form->field($model, 'field25') ?>

    <?php // echo $form->field($model, 'field24') ?>

    <?php // echo $form->field($model, 'field23') ?>

    <?php // echo $form->field($model, 'field22') ?>

    <?php // echo $form->field($model, 'field21') ?>

    <?php // echo $form->field($model, 'field20') ?>

    <?php // echo $form->field($model, 'field19') ?>

    <?php // echo $form->field($model, 'field18') ?>

    <?php // echo $form->field($model, 'field17') ?>

    <?php // echo $form->field($model, 'field16') ?>

    <?php // echo $form->field($model, 'field15') ?>

    <?php // echo $form->field($model, 'field14') ?>

    <?php // echo $form->field($model, 'field13') ?>

    <?php // echo $form->field($model, 'field7') ?>

    <?php // echo $form->field($model, 'field6') ?>

    <?php // echo $form->field($model, 'field0') ?>

    <?php // echo $form->field($model, 'doctorid') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
