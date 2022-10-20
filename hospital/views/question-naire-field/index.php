<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\QuestionNaireFieldSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="question-naire-field-index">
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
                        <?php
                        $columns[4]= [
                            [
                                'attribute' => '姓名',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>37]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '出生日期',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>38]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '手机号	',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>39]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '体温	',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>40]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '症状	',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>41]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '流行病学史',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>42]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '进入时间',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>43]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '是否预约',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>44]);
                                    return $qna->answer?'是':'否';
                                }
                            ],
                            [
                                'attribute' => '有无到过其他医疗机构的发热门诊就诊',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>45]);
                                    return $qna->answer?'是':'否';
                                }
                            ],
                            [
                                'attribute' => '签字',
                                'format' => 'html',
                                'value' => function ($e) {
                                    return Html::img($e->sign, ['width' => '100px']);
                                }
                            ],
                            'createtime:datetime',

                        ];
                        $columns[1]= [
                            [
                                'attribute' => '姓名',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>1]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '手机号',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>2]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '身份证',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>3]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '家庭地址	',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>4]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '21天内是否有境外	',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>5]);
                                    return $qna->answer?'是':'否';
                                }
                            ],
                            [
                                'attribute' => '感染者接触史',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>9]);
                                    return $qna->answer?'是':'否';
                                }
                            ],
                            [
                                'attribute' => '相关工作人员',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>10]);
                                    return $qna->answer?'是':'否';
                                }
                            ],
                            [
                                'attribute' => '14天内小范围症状',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>11]);
                                    return $qna->answer?'是':'否';
                                }
                            ],
                            [
                                'attribute' => '本人症状（发热等）',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>14]);
                                    return $qna->answer?'是':'否';
                                }
                            ],
                            [
                                'attribute' => '体温',
                                'value' => function ($e) {
                                    $qna=\common\models\QuestionNaireAnswer::findOne(['qnfid'=>$e->id,'qnaid'=>36]);
                                    return $qna->answer;
                                }
                            ],
                            [
                                'attribute' => '签字',
                                'format' => 'html',
                                'value' => function ($e) {
                                    return Html::img($e->sign, ['width' => '100px']);
                                }
                            ],
                            'createtime:datetime',

                        ];

                        ?>
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,

                            'columns' => $columns[$qnid],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>