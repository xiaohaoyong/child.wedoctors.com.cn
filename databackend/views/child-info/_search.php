<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model databackend\models\user\ChildInfoSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="child-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['id' => 'child', 'class' => 'form-inline'],
    ]); ?>
    <?php if (\Yii::$app->user->identity->type == 1) { ?>

        <?= $form->field($model, 'admin')->dropdownList(\common\models\UserDoctor::find()->select('name')->indexBy('hospitalid')->andFilterWhere(['>', 'userid', '37'])->andFilterWhere(['county' => \Yii::$app->user->identity->county])->column(), ['prompt' => '请选择']) ?>
    <?php } ?>

    <?= $form->field($model, 'child_type')->dropDownList(\common\models\Article::$childText, ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'level')->dropdownList([1 => '已签约', 2 => '已签约未关联', 3 => '未签约'], ['prompt' => '请选择']) ?>
    <?= $form->field($model, 'docpartimeS')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'docpartimeE')->widget(\kartik\date\DatePicker::className(), ['pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true
    ]]) ?>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'userphone') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        <?= Html::button('下载', ['data-toggle' => 'modal', 'data-target' => '#myModal','class' => 'btn btn-primary']) ?>

        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$sessionid = Yii::$app->session->getId();
$updateJs = <<<JS
    jQuery("#down").click(function () {
       
        jQuery('#progress_title').show();
        jQuery('#progress_title').html('数据准备中请稍等');
        jQuery('#progress_content').hide();
        jQuery('#progress_line').width("0%");
        jQuery('#progress_down').hide();
        jQuery('#down').hide();
        
        
        var wsl = 'ws://127.0.0.1:9501';
        ws = new WebSocket(wsl);// 新建立一个连接         
        // 如下指定事件处理
        ws.onopen = function () {
            var data={};
            data.type='Apply';
            data.token="$sessionid";
            data.data=jQuery('#child').serialize();
            console.log("open");
            ws.send(JSON.stringify(data));
        };
        // 接收消息
        ws.onmessage = function (evt) {
            console.log(evt.data);
            var obj =eval('('+evt.data+')');
            if (obj.state==1){
                if(obj.line==100){
                    jQuery('#progress_title').html('即将完成请稍后');
                }else{
                    jQuery('#progress_title').html('正在准备Excel文件');
                }
                jQuery('#progress_line').width(obj.line+"%");
                jQuery('#progress_down').show();
            }
            if (obj.state==2){
                jQuery('#down').show();
                jQuery('#progress_title').html('文件已生成，请点击下方链接下载');
                jQuery('#progress_down').hide();
                jQuery('#progress_content').show();
                jQuery('#progress_content').html(obj.url);
            }
            /*ws.close();*/
        };
        // 关闭
        ws.onclose = function (evt) {
            console.log('WebSocketClosed!');
        };
        // 报错
        ws.onerror = function (evt) {
            console.log('WebSocketError!');
        };
        
    });
JS;
$this->registerJs($updateJs);
?>

<?php

use yii\bootstrap\Modal;

Modal::begin([
    'id' => 'myModal',
    'header' => '下载提示',
]);
?>
<?= Html::button('重新下载', ['id' => 'down', 'class' => 'btn btn-primary']) ?>
<h4 id="progress_title" style="display: none">数据准备中请稍等</h4>
<div class="progress active" id="progress_down" style="display: none;">
    <div id='progress_line' class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar"
         aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 1%">
        <span class="sr-only">40% Complete (success)</span>
    </div>
</div>
<div id="progress_content" style="display: none">

</div>
<div id="progress_list">
    <h4 id="progress_title">下载历史</h4>
    <table class="table table-striped">
        <tbody><tr>
            <th style="width: 10px">#</th>
            <th>链接</th>
            <th>下载</th>
        </tr>
        <?php
            $dataUserTask=\common\models\DataUserTask::findAll(['datauserid'=>Yii::$app->user->id,'state'=>1]);
            foreach($dataUserTask as $k=>$v){
        ?>
        <tr>
            <td><?=$k+1?>.</td>
            <td><?=$v->result?></td>
            <td><a href="http://static.wedoctors.com.cn/<?=$v->result?>" target="_blank">点击下载</a> </td>
        </tr>
        <?php }?>
        </tbody></table>
</div>
<?php Modal::end(); ?>
