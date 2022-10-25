<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="question-view">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">


                <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                            'id',
              'qid',
                    [
                        'attribute' => 'createtime',
                        'label' => '创建时间',
                        'value' => function ($model){
                            return date("Y-m-d H:i:s",$model->createtime);
                        },
                    ],

            'userid',
                    [
                        'attribute' => 'is_satisfied',
                        'label' => '回复及时性满意度',
                        'value' => function ($model){
                            if($model->is_satisfied){
                                return \common\models\QuestionComment::$satisfiedArr[$model->is_satisfied];
                            }

                        },
                    ],
                    [
                        'attribute' => 'is_solve',
                        'label' => '创建时间',
                        'value' => function ($model){
                            if($model->is_solve){
                                return \common\models\QuestionComment::$solvedArr[$model->is_solve];
                            }
                        },
                    ],


                ],
                ]) ?>


            </div>
        </div>
    </div>

    <div class="box-body">
        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row">
                <?= \yii\grid\GridView::widget([
                    'options' => ['class' => 'col-sm-12'],
                    'dataProvider' => $dataProvider,

                    'columns' => [
                        'content',
                        [
                            'label' => '回复时间',
                            'format'=>['date','php:Y-m-d H:i:s'],
                            'value' => 'createtime',
                        ],
                        [
                            'attribute' => 'userid',
                            'value' => function ($e) {
                                if ($e->is_doctor) {
                                    $name = \common\models\UserDoctor::findOne(['userid' => $e->userid])->name;
                                } else {
                                    $name = "用户：" . $e->userid;
                                }
                                return $name;
                            }
                        ],
                        //'qid',
                        // 'level',


                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>