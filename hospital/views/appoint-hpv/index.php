<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\AppointHpvSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="appoint-hpv-index">
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

                                'name',
                                'phone',
                                [

                                    'attribute' => 'date',
                                    'value' => function ($e) {
                                        if($e->date>'1990-01-01'){
                                            return $e->date;
                                        }else{
                                            return '无';
                                        }
                                    }
                                ],
                                [

                                    'attribute' => 'state',
                                    'value' => function ($e) {
                                        return \common\models\AppointHpv::$stateText[$e->state];
                                    }
                                ],
                                // 'userid',
                                // 'doctorid',
                                [

                                    'attribute' => 'vid',
                                    'value' => function ($e) {
                                        return \common\models\Vaccine::findOne($e->vid)->name;
                                    }
                                ],
                                'cratettime:datetime',

                                // 'img',

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
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>