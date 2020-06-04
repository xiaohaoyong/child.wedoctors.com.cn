<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\UserDoctorAppointSearchModels */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '预约设置状态';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="hospital-appoint-index">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap">
                    <div class="row">
                        <div id="w1" class="col-sm-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>名称</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach(\common\models\HospitalAppoint::$typeText as $k=>$v){
                                    ?>
                                <tr>
                                    <td><?=$v?></td>
                                    <td>
                                        <?=in_array($k,$userDoctorAppoint)?"已设置":"未设置"?>，
                                        <?=in_array($k,$types)?"已开通":"未开通"?>
                                    </td>
                                    <td><?=Html::a('修改',['hospital-appoint/create','type'=>$k])?></td>
                                </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <!-- /.box-header -->
                                <div class="box-body">

                                    <?php
                                    $form = \yii\widgets\ActiveForm::begin(); ?>

                                    <?= $form->field($model, 'appoint_intro')->textarea(['rows'=>3]) ?>


                                    <div class="form-group">
                                        <?= Html::submitButton($model->isNewRecord ? '提交': '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                                            'btn btn-primary']) ?>
                                    </div>

                                    <?php \yii\widgets\ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>