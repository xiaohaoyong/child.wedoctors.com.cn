<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\HospitalMailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="hospital-mail-index">
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

                                    'attribute' => 'content',
                                    'value' => function ($e) {
                                        return mb_substr($e->content, 0, 20, 'utf-8') . "...";

                                    }
                                ],
                                [

                                    'attribute' => 'touser',
                                    'value' => function ($e) {
                                        return $e->touser ? \common\models\UserDoctor::findOne(['hospitalid' => $e->touser])->name : "全部";

                                    }
                                ],
                                'createtime:datetime',

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
                                    <li>{show}</li>
                                </ul>
                            </div>
                            ',

                                    'buttons' => [
                                        'show' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-database"></span> 查看情况', \yii\helpers\Url::to(['/hospital-mail-show', 'HospitalMailShow[mailid]' => $model->id]));
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