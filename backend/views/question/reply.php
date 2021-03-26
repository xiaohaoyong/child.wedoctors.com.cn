<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '列表', 'url' => ['index']],
];
?>
<div class="question-view">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'createtime:datetime',
                        [
                            'attribute' => '生日',
                            'value' => function ($e) {
                                return date('Y-m-d', \common\models\QuestionInfo::findOne(['qid' => $e->id])->birthday);
                            }
                        ],
                        [
                            'attribute' => '性别',
                            'value' => function ($e) {
                                $info = \common\models\QuestionInfo::findOne(['qid' => $e->id]);
                                return \common\models\QuestionInfo::$sexText[$info->sex];
                            }
                        ],
                        [
                            'attribute' => '问题',
                            'value' => function ($e) {
                                $info = \common\models\QuestionInfo::findOne(['qid' => $e->id]);
                                return $info->content;
                            }
                        ],
                        [
                            'attribute' => '提问社区',
                            'value' => function ($e) {
                                $info = \common\models\UserDoctor::findOne(['userid' => $e->doctorid]);
                                return $info->name;
                            }
                        ],
                    ],
                ]) ?>

            </div>
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= \yii\grid\GridView::widget([
                            'options' => ['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,

                            'columns' => [
                                'content',
                                'createtime:datetime',
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
                                </ul>
                            </div>
                            ',
                                    'buttons' => [
                                        'update' => function ($url, $model, $key) {
                                            return \yii\helpers\Html::a('<span class="glyphicon glyphicon-pencil"> 编辑</span>', '/question-reply/update?id='.$model->id);
                                        }
                                    ]
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <?php $form = \yii\widgets\ActiveForm::begin(); ?>

                <?= $form->field($reply, 'content')->textarea()->label('回复') ?>
                <?= $form->field($reply, 'userid')->hiddenInput(['value'=>47156])->label(false) ?>
                <?= $form->field($reply, 'is_doctor')->hiddenInput(['value'=>1])->label(false) ?>
                <?= $form->field($reply, 'qid')->hiddenInput(['value'=>$model->id])->label(false) ?>


                <div class="form-group">
                    <?= Html::submitButton($reply->isNewRecord ? '提交'                    : '提交', ['class' => $reply->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                </div>

                <?php \yii\widgets\ActiveForm::end(); ?>
            </div>

        </div>
    </div>
</div>