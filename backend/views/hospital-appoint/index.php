<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\HospitalAppointSearchModels */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="hospital-appoint-index">
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

                                    'attribute' => 'doctorid',
                                    'value' => function ($e) {
                                        return \common\models\UserDoctor::findOne(['userid' => $e->doctorid])->name;
                                    }
                                ],
                                [

                                    'attribute' => 'type',
                                    'value' => function ($e) {
                                        return \common\models\HospitalAppoint::$typeText[$e->type];
                                    }
                                ],
                                [

                                    'attribute' => 'cycle',
                                    'value' => function ($e) {
                                        return \common\models\HospitalAppoint::$cycleText[$e->cycle];
                                    }
                                ],
                                [

                                    'attribute' => 'delay',
                                    'value' => function ($e) {
                                        return $e->delay;
                                    }
                                ],
                                [

                                    'attribute' => 'weeks',
                                    'value' => function ($e) {
                                        return $e->weeks;
                                    }
                                ],
                                [

                                    'attribute' => 'interval',
                                    'value' => function ($e) {
                                        return \common\models\HospitalAppoint::$intervalText[$e->interval];
                                    }
                                ],
                                [

                                    'attribute' => 'updateInterval',
                                    'value' => function ($e) {
                                        return date('Y-m-d',$e->updateInterval);
                                    }
                                ],
                                [


                                    'attribute' => '是否开通',
                                    'value' => function ($e) {
                                        $doctor = \common\models\UserDoctor::findOne(['userid' => $e->doctorid]);
                                        return strpos((string)$doctor->appoint, (string)$e->type) !== false || $doctor->appoint == $e->type ? '是' : '否';
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
                                    <li>{true}</li>
                                </ul>
                            </div>
                            ',
                                    'buttons' => [
                                        'true' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-database"></span> 去开通', \yii\helpers\Url::to(['user-doctor/update', 'id' => $model->doctorid]));
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