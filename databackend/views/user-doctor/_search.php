<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserDoctorSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-doctor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id'=>'user-doctor',
        'options' => ['class' => 'form-inline'],
    ]); ?>
    <?= $form->field($model, 'docpartimeS')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'docpartimeE')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]]) ?>
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <?= Html::button('下载', ['id' => 'down', 'class' => 'btn btn-primary']) ?>

        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$updateJs = <<<JS
jQuery("#down").click(function () {
        //过jquery为action属性赋值
        if(confirm("时间搜索仅影响签约总数")){
            jQuery("#user-doctor").attr('action',"down");    //通
            jQuery("#user-doctor").submit();    //提交ID为myform的表单
        }
    });
JS;
$this->registerJs($updateJs);
?>

