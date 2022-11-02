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
                                        }else{
                                            return '否';
                                        }

                                    }
                                ],


                                [
                                    'class' => 'common\components\grid\ActionColumn',
                                    'template' => '
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                       
                                    <li>{update}</li>
                                    <li>{delete}</li>
                                    <li>{reply}</li>
                                </ul>
                            </div>
                            ',
                                    'buttons' => [
                                        'reply' => function ($url, $model, $key) {
                                            return \yii\helpers\Html::a('<span class="fa fa-share"> 回复</span>', '/question/reply?id='.$model->id);
                                        }
                                    ]
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>