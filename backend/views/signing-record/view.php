<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SigningRecord */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Signing Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
];

$pagedata = '';

if ($model->type == 1)
{
    $pagedata = $model->get_pregnancy_info($model->userid);
}

?>
<div class="signing-record-view">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                if ($model->type == 1)
                {
                    echo DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'type',
                                'value' => $model->type == 2 ? '宝宝'  : '孕妈',
                            ],
                            [
                                'attribute'=>'sign_item_id_from',
                                'value'=>function ($model){
                                    return $model->convert_iid($model->sign_item_id_from);
                                }
                            ],

                            [
                                'attribute'=>'sign_item_id_to',
                                'value'=>function ($model){
                                    return $model->convert_iid($model->sign_item_id_to);
                                }
                            ],
                            [
                                'attribute'=>'status',
                                'format'=>'raw',
                                'value'=>function ($model){
                                    if ($model->status == 0)
                                        return '未审核';
                                    elseif($model->status == 1)
                                        return '<font color="green">审核通过</font>';
                                    elseif($model->status == 2)
                                        return '<font color="red">审核未通过</font>';
                                }
                            ],
                            [
                                'attribute'=>'createtime',
                                'value'=>function ($model){
                                    return date('Y-m-d H:i:s',$model->createtime);
                                }
                            ],
                            [
                                'label'=>'末次月经',
                                'value'=>$pagedata['field16'] ? date('Y:m:d H:i:s',$pagedata['field16']) : ''
                            ],
                            [
                                'label'=>'户籍类型',
                                'value'=>$pagedata['field7'] == 11 ? '本市户籍' : '外地户籍'
                            ],
                            [
                                'label'=>'孕妇户籍地',
                                'value'=>\common\models\Area::$province[$pagedata['field7']]
                            ],
                            [
                                'label'=>'丈夫户籍地',
                                'value'=>\common\models\Area::$province[$pagedata['field39']]
                            ],
                            [
                                'attribute'=>'info_pics',
                                'value'=>function ($model){
                                    return '';
                                }
                            ],
                        ],
                    ]) ;

                    $pics = json_decode($model->info_pics);
                    if ($pics && count($pics))
                    {
                        $html= '';
                        foreach ($pics as $v)
                        {
                            $html .= '<a target="_blank" href="'.$v.'"><img src="'.$v.'" style="width:100px;height:100px"></a>&nbsp;&nbsp;';
                        }
                        echo $html;
                    }
                }
                else
                {
                    echo DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'type',
                                'value' => $model->type == 2 ? '宝宝'  : '孕妈',
                            ],
                            [
                                'attribute'=>'sign_item_id_from',
                                'value'=>function ($model){
                                    return $model->convert_iid($model->sign_item_id_from);
                                }
                            ],

                            [
                                'attribute'=>'sign_item_id_to',
                                'value'=>function ($model){
                                    return $model->convert_iid($model->sign_item_id_to);
                                }
                            ],
                            [
                                'attribute'=>'status',
                                'format'=>'raw',
                                'value'=>function ($model){
                                    if ($model->status == 0)
                                        return '未审核';
                                    elseif($model->status == 1)
                                        return '<font color="green">审核通过</font>';
                                    elseif($model->status == 2)
                                        return '<font color="red">审核未通过</font>';
                                }
                            ],
                            [
                                'attribute'=>'createtime',
                                'value'=>function ($model){
                                    return date('Y-m-d H:i:s',$model->createtime);
                                }
                            ],
                            [
                                'attribute'=>'info_pics',
                                'value'=>function ($model){
                                    return '';
                                }
                            ],
                        ],
                    ]) ;

                    $pics = json_decode($model->info_pics);
                    if ($pics && count($pics))
                    {
                        $html= '';
                        foreach ($pics as $v)
                        {
                            $html .= '<a target="_blank" href="'.$v.'"><img src="'.$v.'" style="width:100px;height:100px"></a>&nbsp;&nbsp;';
                        }
                        echo $html;
                    }
                }

                ?>
            </div>
        </div>


        <form action="/signing-record/audit" method="post" id="audit_form" name="audit_form">

            添加备注：<br />
            <textarea name="remark" id="remark"  cols="80" rows="3" wrap="hard"><?=$model->remark?></textarea>
            <input id="id" name="id"  type="hidden" value="<?=$model->id?>">
            <input id="status" name="status" type="hidden" value="">
            <input type="hidden" name="<?= \Yii::$app->request->csrfParam; ?>" value="<?= \Yii::$app->request->getCsrfToken();?>">
        </form>

        <?php
        if ($model->status != 1) {

            ?>
            <button onclick="submit_audit(1)" class="btn btn-primary">审核通过</button>
            &nbsp;&nbsp;&nbsp;
            <?php
        }
        ?>
        <?php
        if ($model->status != 2) {

            ?>
            <button onclick="submit_audit(2)" class="btn btn-default">审核不通过</button>
            <?php
        }
        ?>

        <script type="text/javascript">
            function submit_audit(status) {
                document.getElementById("status").value = status;
                document.getElementById("audit_form" ).submit();
            }

        </script>

    </div>
</div>