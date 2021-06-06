<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\HealthRecordsSchoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="health-records-school-index">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-header with-border">
<!--                <h3 class="box-title">学生签约填表二维码(建档表单)：</h3>-->
<!--                <div>-->
<!--                    <img src="https://api.child.wedoctors.com.cn/image/doctor?id=--><?//=$doctorid?><!--" width="200" height="200">-->
<!--                </div>-->
                <!-- /.box-tools -->
                <h3 class="box-title">学生签约填表二维码(基本信息表单)：</h3>
                <div>
                    <img src="https://api.child.wedoctors.com.cn/image/doctor2?id=<?=$doctorid?>" width="200" height="200">
                </div>
                <!-- /.box-tools -->
            </div>
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,

                            'columns' => [

                                'name',
                                'doctorid',
                                'sign1',
                                'sign2',
                                // 'doctor_name',

                                [
                                    'class' => 'common\components\grid\ActionColumn',
                                    'template' => '
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                                                                    <li>{view}</li>

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