<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserParentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '家庭数据';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="user-parent-index">
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
                                'userid',
                                'mother',
                                [

                                    'attribute' => 'mother_phone',
                                    'value' => function ($e) {
                                        return substr_replace($e->mother_phone, '****', 3, 4);

                                    }
                                ],

                                [

                                    'attribute' => 'mother_id',
                                    'value' => function ($e) {
                                        return substr_replace($e->mother_id, '********', 6, 8);
                                    }
                                ],
                                'father',
                                [

                                    'attribute' => 'father_phone',
                                    'value' => function ($e) {
                                        return substr_replace($e->father_phone, '****', 3, 4);
                                    }
                                ],
                                'father_birthday',
                                'state',
                                'address',
                                [
                                    'attribute' => 'source',
                                    'format' => 'raw',
                                    'value' => function ($e) {
                                        $doctor = \common\models\UserDoctor::findOne(['hospitalid' => $e->source]);

                                        return $doctor->name;

                                    }
                                ],
//             'field34',
//             'field33',
//             'field30',
//             'field29',
                                'field28',
//             'field12',
//             'field11',
//             'field1',
//             'province',
//             'county',
//             'city',
                                //'fbirthday',

                                [
                                    'class' => 'common\components\grid\ActionColumn',
                                    'template' => '<div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>{update}</li>
                                    <li>{delete}</li>
                                    <li>{view}</li>
                                    <li>{children}</li>
                                    <li>{login}</li>

                                </ul>
                            </div>',
                                    'buttons' => [
                                        'children' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-database"></span> 查看关联儿童', \yii\helpers\Url::to(['child/index', 'ChildSearch[userid]' => $model->userid]));
                                        },
                                        'login' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-database"></span> 查看登录情况', \yii\helpers\Url::to(['user-login/index', 'UserLoginSearch[userid]' => $model->userid]));
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