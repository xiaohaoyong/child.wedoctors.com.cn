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
                                        $info = \common\models\QuestionInfo::findOne(['qid' => $e->id]);
                                        return $info->content;
                                    }
                                ],
                                [
                                    'label' => '创建时间',
                                    'format'=>['date','php:Y-m-d H:i:s'],
                                    'value' => 'createtime',
                                ],
                                [
                                    'label' => '回复时间',
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



                                // 'orderid',
                                // 'level',
                                // 'state',


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