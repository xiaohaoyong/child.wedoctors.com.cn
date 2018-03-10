<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\operate\Operate */
/* @var $form yii\widgets\ActiveForm */

foreach($all as $k=>$v)
{
    $rs = [];
    $rs['id'] = $v['id'];
    $rs['value'] = $v['id'].'-'.$v['parent_id']; //自定义隐藏域内容
    $rs['parent'] = $v['parent_id'] ? $v['parent_id'] : "#";
    $rs['text'] = $v['description'];
    if(in_array($v['id'],$operate))
    {
        $rs['state']["selected"] = true;
        $rs['state']["opened"] = true;
    }elseif(intval($v['parent_id'])){
        $rs["icon"] = "fa fa-check fa-ellipsis-v";
    }

    $data[] = $rs;
}

?>

<div class="operate-form">

        <?= \common\widgets\JstreeWidget::widget([
            'id' => 'operate',
            'field' => 'operate_id',
            'clientOptions'=>[
                'plugins' => ['checkbox'],
                'core' => ['data' => $data],
            ]
        ]) ?>

        <div id="operate"></div>

        <hr>

        <?= Html::button('提交',['class' => 'btn btn-default']) ?>

</div>

<?php
$js = <<<JS
    $("button").on('click',function() {
        if($('[name="operate_id[]"]').length > 0)
        {
            $("#operateform").submit();
        }
    });
JS;

$this->registerJs($js);
?>