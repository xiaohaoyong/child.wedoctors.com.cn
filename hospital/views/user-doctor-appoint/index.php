<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\UserDoctorAppointSearchModels */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="user-doctor-appoint-index">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div id="w1" class="col-sm-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>名称</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>体检预约</td>
                                    <td><?=Html::a('修改',['user-doctor-appoint/update','type'=>1])?></td>
                                </tr>
                                <tr>
                                    <td>疫苗预约</td>
                                    <td><?=Html::a('修改',['user-doctor-appoint/update','type'=>2])?></td>
                                </tr>
                                <tr>
                                    <td>微量元素</td>
                                    <td><?=Html::a('修改',['user-doctor-appoint/update','type'=>3])?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>