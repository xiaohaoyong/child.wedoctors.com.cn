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
            <?= Html::button('下载', ['id' => 'down', 'class' => 'btn btn-primary']) ?>

            <div class="help-block"></div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$updateJs = <<<JS
    jQuery("#down").click(function () {
       

        var wsl = 'ws://127.0.0.1:9501';
        ws = new WebSocket(wsl);// 新建立一个连接
        // 如下指定事件处理
        ws.onopen = function () {
             ws.send('Test!');
        };
        // 接收消息
        ws.onmessage = function (evt) {
            console.log(evt.data);
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