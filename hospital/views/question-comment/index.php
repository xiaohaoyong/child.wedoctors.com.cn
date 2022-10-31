<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\QuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
                                'qid',
                                'userid',
                                [
                                    'attribute' => '问题',
                                    'value' => function ($e) {
                                        $info = \common\models\QuestionInfo::findOne(['qid' => $e->qid]);
                                        return $info->content;
                                    }
                                ],
                                [
                                    'label' => '创建时间',
                                    'format'=>['date','php:Y-m-d H:i:s'],
                                    'value' => 'createtime',
                                ],

                                [
                                    'attribute' => '指定社区',
                                    'value' => function ($e) {
                                        $doctor=\common\models\UserDoctor::find()->where(['userid'=>$e->userid])->one();
                                        return $doctor->name;
                                    }
                                ],

                                [
                                    'attribute' => 'is_satisfied',
                                    'value' => function ($e) {
                                       if($e->is_satisfied){
                                           return \common\models\QuestionComment::$satisfiedArr[$e->is_satisfied];
                                       }
                                    }
                                ],
                                [
                                    'attribute' => 'is_solve',
                                    'value' => function ($e) {
                                        if($e->is_solve){
                                            return \common\models\QuestionComment::$solvedArr[$e->is_solve];
                                        }
                                    }
                                ],

                                // 'orderid',
                                // 'level',
                                // 'state',


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

                                                return Html::a('查看', ['question-comment/view', 'id'=>$model->id], $options);
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