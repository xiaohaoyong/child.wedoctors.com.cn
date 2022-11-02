<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\QuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
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
                                'id',
                                'userid',
                                [
                                    'label' => '创建时间',
                                    'format'=>['date','php:Y-m-d H:i:s'],
                                    'value' => 'createtime',
                                ],
                                [
                                    'attribute' => '问题',
                                    'value' => function ($e) {
                                        $info = \common\models\QuestionInfo::findOne(['qid' => $e->id]);
                                        return $info->content;
                                    }
                                ],
                                [
                                    'attribute' => 'doctorid',
                                    'value' => function ($e) {
                                        $doctor=\common\models\UserDoctor::findOne($e->doctorid);
                                        return $doctor->name;
                                    }
                                ],
                                // 'orderid',
                                // 'level',
                                [
                                    'attribute' => '问题状态',//问题状态
                                    'value' => function ($e) {
                                        return \common\models\Question::$stateText[$e->state];
                                    }
                                ],
                                [
                                    'attribute' => '是否评价',//是否评价
                                    'value' => function ($e) {
                                        if($e->is_comment == 1){
                                            return '是';
                                           // return \yii\helpers\Html::a('是', '/question-comment/view?qid='.$e->id);

                                        }else{
                                            return '否';
                                        }

                                    }
                                ],

                                [
                                    'class' => 'common\components\grid\ActionColumn',
                                    'header'=>'操作',
                                    'template' => '{update}{delete}{reply}{audit}{view}',
                                    'buttons' => [
                                        'update' => function($url, $model) {
                                            return Html::a('修改', ['question/update', 'id' => $model->id]);
                                        },


                                        'view' => function($url, $model) {
                                            $options = [
                                                'title' => '结束会话',
                                                'style' => 'margin-left:5px;',
                                                'data-method' => 'post',
                                                'data-confirm' => '确认结束会话吗'
                                            ];
                                            //已回复
                                            if($model->state==1 ) {
                                                return Html::a('结束会话', ['question/update-state', 'id' => $model->id], $options);
                                            }

                                        },
                                        'delete' => function($url, $model) {
                                            $options = [
                                                'title' => '删除',
                                                'style' => 'margin-left:5px;',
                                                'data-method' => 'post',
                                                'data-confirm' => '数据删除后将无法恢复，确认删除？'
                                            ];
                                            return Html::a('删除', ['question/delete', 'id'=>$model->id],$options);
                                        },
                                        'reply' => function ($url, $model, $key) {
                            //未回复，已回复
                                            if(in_array($model->state,[0,1])){
                                                return \yii\helpers\Html::a(' 回复', '/question/reply?id='.$model->id);

                                            }
                                        }


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