<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\AppointSearchModels */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appoint-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,

                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [

                                    'attribute' => '儿童姓名',
                                    'value' => function ($e) {
                                        return \common\models\ChildInfo::findOne(['id'=>$e->childid])->name;
                                    }
                                ],
                                'createtime:datetime',
                                'appoint_time:datetime',
                                'appoint_date',
                                'phone',
                                'state',
                                [
                                    'class' => 'common\components\grid\ActionColumn',
                                    'template' => '
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>{true}</li>
                                </ul>
                            </div>
                            ',
                                    'buttons' => [
                                        'true' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-database"></span> 完成', \yii\helpers\Url::to(['article-user/index']),['data-confirm'=>"是否确定已完成"]);
                                        },
                                    ],
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>