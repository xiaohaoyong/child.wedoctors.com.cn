<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\ActiveForm;

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
                是否开通留言板：
                <?= \dosamigos\switchinput\SwitchBox::widget([
                    'name' => 'is_question',
                    'checked' => $userDoctor['is_question'],
                    'clientOptions' => [
                        'size' => 'mini',
                        'onColor' => 'success',
                        'offColor' => 'danger'
                    ],
                    'clientEvents' => [
                        'switchChange.bootstrapSwitch' => "function(e){
                            ajax(e.currentTarget.checked);
                        }"
                    ],
                ]);?>


            </div>
        </div>
        </div>
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
                                    'attribute' => '问题状态',
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
                                    'template' => '
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                               
                                    <li>{reply}</li>
                                     <li>{view}</li>
                                </ul>
                            </div>
                            ',
                                    'buttons' => [
                                        'reply' => function ($url, $model, $key) {

                                            if(in_array($model->state,[0,1])){
                                                return \yii\helpers\Html::a('<span class="fa fa-share"> 回复</span>', '/question/reply?id=' . $model->id);

                                            }else{
                                                return \yii\helpers\Html::a(' 查看', '/question/reply?id='.$model->id);
                                            }
                                        },
                                        'view' => function ($url, $model, $key) {
                                            $options = [
                                                'title' => '结束会话',
                                                'style' => 'margin-left:5px;',
                                                'data-method' => 'post',
                                                'data-confirm' => '确认结束会话吗'
                                            ];
                                            if($model->state==1 ) {
                                                return Html::a('结束会话', ['question/update-state', 'id' => $model->id],$options);
                                            }
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
<script>
    function ajax($checked) {

        if($checked){
            $is_question=1;
        }else{
            $is_question=0;
        }
        $.get('/question?is_question='+$is_question,function (e) {

        })
    }
</script>