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
                                <tr>
                                    <td>体检预约</td>
                                    <td>
                                        <?=in_array(1,$types)?"已设置":"未设置"?>，
                                        <?=in_array(1,$userDoctorAppoint)?"已开通":"未开通"?>
                                    </td>
                                    <td><?=Html::a('修改',['hospital-appoint/create','type'=>1])?></td>
                                </tr>
                                <tr>
                                    <td>疫苗预约</td>
                                    <td>
                                        <?=in_array(2,$types)?"已设置":"未设置"?>，
                                        <?=in_array(2,$userDoctorAppoint)?"已开通":"未开通"?>
                                    </td>
                                    <td><?=Html::a('修改',['hospital-appoint/create','type'=>2])?></td>
                                </tr>
                                <tr>
                                    <td>微量元素</td>
                                    <td>
                                        <?=in_array(3,$types)?"已设置":"未设置"?>，
                                        <?=in_array(3,$userDoctorAppoint)?"已开通":"未开通"?>
                                    </td>
                                    <td><?=Html::a('修改',['hospital-appoint/create','type'=>3])?></td>
                                </tr>
                                <tr>
                                    <td>成人疫苗</td>
                                    <td>
                                        <?=in_array(4,$types)?"已设置":"未设置"?>，
                                        <?=in_array(4,$userDoctorAppoint)?"已开通":"未开通"?>
                                    </td>
                                    <td><?=Html::a('修改',['hospital-appoint/create','type'=>4])?></td>
                                </tr>
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