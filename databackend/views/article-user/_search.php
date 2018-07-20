<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model databackend\models\article\ArticleUserSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>
    <?= $form->field($model, 'userid')->dropdownList(\common\models\UserDoctor::find()->select('name')->indexBy('userid')->andFilterWhere(['>','userid','37'])->andFilterWhere(['county'=>\Yii::$app->user->identity->county])->column(),['prompt'=>'请选择']) ?>

    <?php  echo $form->field($model, 'level')->dropDownList(\common\models\ArticleUser::$levelText,['prompt'=>'请选择']) ?>

    <?php  echo $form->field($model, 'child_type')->dropDownList(\common\models\Article::$childText,['prompt'=>'请选择']) ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
