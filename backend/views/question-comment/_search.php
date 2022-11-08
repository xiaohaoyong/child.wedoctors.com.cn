<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\QuestionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>
    <?= $form->field($model, 'qid') ?>
    <?= $form->field($model, 'userid') ?>
    <?php $county = \common\models\Area::$city[11] ? \common\models\Area::$county[11] : []; ?>
    <?= $form->field($model, 'county')->dropDownList($county, [
        'prompt' => '请选择',
        'onchange' => '
            $("#' . Html::getInputId($model, 'doctorid') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $.post("' . \yii\helpers\Url::to(['user-doctor/get']) . '?UserDoctorSearchModel[county]="+$(this).val(),function(data){
                $("#' . Html::getInputId($model, 'doctorid') . '").html(data);
            });',
    ]) ?>
    <?= $form->field($model, 'doctorid')->dropDownList(\common\models\UserDoctor::find()->select('name')->indexBy('userid')->where(['county' => $model['county']])->column(), ['prompt' => '请选择']) ?>


    <?= $form->field($model, 'startDate')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'endDate')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]]) ?>






    <?=$form->field($model,'is_satisfied')->dropDownList(\common\models\QuestionComment::$satisfiedArr,['prompt'=>'全部']);?>
    <?=$form->field($model,'is_solve')->dropDownList(\common\models\QuestionComment::$solvedArr,['prompt'=>'全部']);?>

    <?php // echo $form->field($model, 'state') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $startDataId = Html::getInputId($model, 'startDate');
    $endDataId = Html::getInputId($model, 'endDate');
    $startMax = $model->endDate ? $model->endDate : '2099-12-31';
    $endMin = $model->startDate ? $model->startDate : date('Y-m-d');


    $js = <<<EOD
getDate ('{$startDataId}','{$endDataId}','{$startMax}','{$endMin}');//发布时间
function getDate (startDataId,endDataId,max,min){
    var start = {
        elem : '#' + startDataId,
        min: '2000-01-01',
        max: max,
        format : 'YYYY-MM-DD',
        istoday: false,
        issure: false,
        choose: function(datas){
            end.min = datas; //开始日选好后，重置结束日的最小日期
            end.start = datas //将结束日的初始值设定为开始日
        }
    };

    var end = {
        elem : '#' + endDataId,
        min: min,
        max: '2099-12-31',
        format : 'YYYY-MM-DD',
        istoday: false,
        issure: false,
        choose: function(datas){
            start.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };

    laydate(start);
    laydate(end);
}


EOD;
    $this->registerJs($js);
    ?>
</div>
