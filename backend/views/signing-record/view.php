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
                                'value'=>function ($model){
                                    if ($model->status == 0)
                                        return '未审核';
                                    elseif($model->status == 1)
                                        return '审核通过';
                                    elseif($model->status == 2)
                                        return '审核不通过';
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
                                'value'=>$pagedata['field16']
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
                    if (count($pics))
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
                                'value'=>function ($model){
                                    if ($model->status == 0)
                                        return '未审核';
                                    elseif($model->status == 1)
                                        return '审核通过';
                                    elseif($model->status == 2)
                                        return '审核不通过';
                                }
                            ],
                            [
                                'attribute'=>'createtime',
                                'value'=>function ($model){
                                    return date('Y-m-d H:i:s',$model->createtime);
                                }
                            ],
                            'info_pics',
                        ],
                    ]) ;
                }

                ?>
            </div>
        </div>
    </div>
</div>