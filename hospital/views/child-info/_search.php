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
    <?php if(\Yii::$app->user->identity->type == 1){?>

        <?= $form->field($model, 'admin')->dropdownList(\common\models\UserDoctor::find()->select('name')->indexBy('hospitalid')->andFilterWhere(['>','userid','37'])->andFilterWhere(['county'=>'1102'])->column(),['prompt'=>'请选择']) ?>
    <?php }?>

    <?= $form->field($model, 'level')->dropdownList([1=>'已签约',2=>'已签约未关联',3=>'未签约'],['prompt'=>'请选择']) ?>
    <?= $form->field($model, 'docpartimeS')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]])?>
    <?= $form->field($model, 'docpartimeE')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]])?>

    <?= $form->field($model, 'birthdayS')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]])?>
    <?= $form->field($model, 'birthdayE')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]])?>
    <?= $form->field($model, 'username')?>
    <?= $form->field($model, 'userphone')?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <?= Html::button('下载', ['id'=>'down','class' => 'btn btn-primary']) ?>
        <?= Html::a('一键导出已签约服务表',"http://static.wedoctors.com.cn/".\Yii::$app->user->identity->hospital.".xlsx", ['id'=>'downnew','class' => 'btn btn-primary','target'=>'_blank']) ?>

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

         // if ("WebSocket" in window)
         //    {
         //       alert("您的浏览器支持 WebSocket!");
         //      
         //       // 打开一个 web socket
         //       var ws = new WebSocket("ws://127.0.0.1:8081/");
         //       
         //       ws.onopen = function()
         //       {
         //           ws.send('测试');
         //           console.log("测试");
         //       };
         //       
         //       ws.onmessage = function (evt) 
         //       { 
         //          var received_msg = evt.data;
         //          alert("数据已接收...");
         //       };
         //       
         //       ws.onclose = function()
         //       { 
         //          // 关闭 websocket
         //          alert("连接已关闭..."); 
         //       };
         //    }
         //   
         //    else
         //    {
         //       // 浏览器不支持 WebSocket
         //       alert("您的浏览器不支持 WebSocket!");
         //    }
        
        
        
    });
JS;
$this->registerJs($updateJs);
?>