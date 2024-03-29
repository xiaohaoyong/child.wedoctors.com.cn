<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hospital\models\user\ChildInfoSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="child-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['id'=>'child','class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'child_type')->dropDownList(\common\models\Article::$childText, ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'level')->dropdownList([1=>'已签约',2=>'已签约未关联',3=>'未签约',4=>'服务过已签约'],['prompt'=>'请选择']) ?>
    <?= $form->field($model, 'docpartimeS')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]])?>
    <?= $form->field($model, 'docpartimeE')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]])?>

    <?= $form->field($model, 'birthdayS')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]])?>
    <?= $form->field($model, 'birthdayE')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autocomplete'=>'off',
        'todayHighlight' => true
    ]])?>
    <?= $form->field($model, 'username')?>
    <?= $form->field($model, 'childname')?>

    <?= $form->field($model, 'userphone')?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <?= Html::button('下载', ['id'=>'down','class' => 'btn btn-primary']) ?>
        <div class="help-block"></div>
    </div>
    <div class="form-group">
        <?= Html::button('一键导出已签约宝宝宣教记录', ['id'=>'downArticle','class' => 'btn btn-primary']) ?>
        <?= Html::a('一键导出已签约服务记录表(0-3)',"http://static.wedoctors.com.cn/".\Yii::$app->user->identity->hospital.".xlsx", ['id'=>'downnew','class' => 'btn btn-primary','target'=>'_blank']) ?>
        <?= Html::a('一键导出已签约服务记录表(0-6)',"http://static.wedoctors.com.cn/".\Yii::$app->user->identity->hospital."-0-6.xlsx", ['id'=>'downnew','class' => 'btn btn-primary','target'=>'_blank']) ?>
        <?= Html::a('一键导出全部已签约',"http://static.wedoctors.com.cn/".\Yii::$app->user->identity->hospital."-all.xlsx", ['id'=>'downnew','class' => 'btn btn-primary','target'=>'_blank']) ?>
        <br>
        <?= Html::a('家庭医生签约服务签约居民基本信息汇总表下载(儿童)',"http://static.wedoctors.com.cn/".\Yii::$app->user->identity->doctorid."-family.xlsx", ['id'=>'downnew','class' => 'btn btn-primary','target'=>'_blank']) ?>
        <?= Html::a('家庭医生签约服务签约居民基本信息汇总表下载(孕妇)',"http://static.wedoctors.com.cn/".\Yii::$app->user->identity->doctorid."-family-pregnancy.xlsx", ['id'=>'downnew','class' => 'btn btn-primary','target'=>'_blank']) ?>

        <div class="help-block"></div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php
$updateJs = <<<JS
    jQuery("#down").click(function () {
        //过jquery为action属性赋值
        if(confirm("点击确定开始下载，请勿刷新或关闭窗口（目前最多下载500条数据），可按照时间筛选")){
            jQuery("#child").attr('action',"/down/child");    //通
            jQuery("#child").submit();    //提交ID为myform的表单
        }
    });
    jQuery("#downArticle").click(function () {
        jQuery("#child").attr('action',"/down/article");    //通
        jQuery("#child").submit();    //提交ID为myform的表单
    });
JS;
$this->registerJs($updateJs);
?>