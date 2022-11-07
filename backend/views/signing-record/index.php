<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SigningRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="signing-record-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>
                                                <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
                                    </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                                                                            <?= GridView::widget([
                            'options'=>['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,
                            
     'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
         [
             'attribute'=>'type',
             'value'=>function ($model){
                 if ($model->type == 1)
                     return '孕妈';
                 elseif($model->type == 2)
                     return '宝宝';
                 else
                     return $model->type;
             }
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
                            'class' => 'common\components\grid\ActionColumn',
                            'template'=>'
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>{view}</li>
                                </ul>
                            </div>
                            ',
                            ],
                            ],
                            ]); ?>
                                                                    </div>
                </div>
            </div>
        </div>
    </div>
</div>