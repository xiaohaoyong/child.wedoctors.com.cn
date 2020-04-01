<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\AppointSearchModels */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appoint-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline', 'id' => 'appoint'],
    ]); ?>

    <?= $form->field($model, 'appoint_dates')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>

    <?php echo $form->field($model, 'appoint_time')->dropDownList(\common\models\Appoint::$timeText, ['prompt' => '请选择']) ?>

    <?php echo $form->field($model, 'state')->dropDownList(\common\models\Appoint::$stateText, ['prompt' => '请选择']) ?>
    <?php echo $form->field($model, 'type')->dropDownList(\common\models\Appoint::$typeText, ['prompt' => '请选择']) ?>

    <?php echo $form->field($model, 'child_name') ?>

    <?php echo $form->field($model, 'phone') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['id' => 'search', 'class' => 'btn btn-primary']) ?>
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
        if(confirm("点击确定开始下载，请勿刷新或关闭窗口（目前最多下载500条数据），可按照时间筛选")){
            jQuery("#appoint").attr('action',"down");    //通
            jQuery("#appoint").submit();    //提交ID为myform的表单
        }
    });

jQuery("#search").click(function () {
        jQuery("#appoint").attr('action',"");    //通
            jQuery("#appoint").submit();    //提交ID为myform的表单
    });
   
JS;
$this->registerJs($updateJs);
?>
