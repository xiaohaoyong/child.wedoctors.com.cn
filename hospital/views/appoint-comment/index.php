<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-index">
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
                                [
                                    'attribute' => '姓名',
                                    'value' => function ($e) {
                                        $info = \common\models\ChildInfo::findOne(['userid' => $e->userid]);
                                        return $info->name;
                                    }
                                ],
                                [
                                    'attribute' => '预约项目',
                                    'value' => function ($e) {
                                        $info = \common\models\Appoint::findOne(['id' => $e->aid]);
                                        return \common\models\Appoint::$typeText[$info->type];
                                    }
                                ],
                                [
                                    'attribute' => '预约时间',
                                    'value' => function ($e) {
                                        $info = \common\models\Appoint::findOne(['id' => $e->aid]);
                                        return date("Y-m-d H:i:s",$info->appoint_time);
                                    }
                                ],
                                [
                                    'attribute' => '评价时间',
                                    'value' => function ($e){
                                        return date("Y-m-d H:i:s",$e->createtime);
                                    }
                                ],
                                [
                                    'attribute' => 'is_envir',
                                    'value' => function ($e) {
                                       if($e->is_envir){
                                           return \common\models\AppointComment::$enverstaffArr[$e->is_envir];
                                       }
                                    }
                                ],
                                [
                                    'attribute' => 'is_process',
                                    'value' => function ($e) {
                                        if($e->is_process){
                                            return \common\models\AppointComment::$enverstaffArr[$e->is_process];
                                        }
                                    }
                                ],
                                [
                                    'attribute' => 'is_staff',
                                    'value' => function ($e) {
                                        if($e->is_staff){
                                            return \common\models\AppointComment::$enverstaffArr[$e->is_staff];
                                        }
                                    }
                                ],
                                [
                                    'attribute' => '整体评价',
                                    'value' => function ($e){
                                        if($e->is_rate==1){
                                            return '好评';
                                        }elseif($e->is_rate==2){
                                            return '中评';
                                        }elseif($e->is_rate==3){
                                            return '差评';
                                        }
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header'=>'操作',
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function($url, $model) {
                                            $options = [
                                                'title' => '查看',
                                                'style' => 'margin-left:5px;'
                                            ];

                                                return Html::a('查看', ['appoint-comment/view', 'id'=>$model->id], $options);
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