<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\UserDoctorAppointSearchModels */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '发送召回提醒';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-index">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap">
                    <?php
                    $form = \yii\widgets\ActiveForm::begin(); ?>
                    <div class="row">
                        <div id="w1" class="col-sm-12">
                            <table id="w0" class="table table-striped table-bordered detail-view">
                                <thead>
                                <tr>
                                    <th>儿童姓名</th>
                                    <td><?= $child->name ?></td>
                                </tr>
                                <tr>
                                    <th>母亲姓名</th>
                                    <td><?= $userParent->mother ?></td>
                                </tr>
                                <tr>
                                    <th>母亲电话</th>
                                    <td><?= $userParent->mother_phone ?></td>
                                </tr>
                                <tr>
                                    <th>父亲姓名</th>
                                    <td><?= $userParent->father ?></td>
                                </tr>
                                <tr>
                                    <th>父亲电话</th>
                                    <td><?= $userParent->father_phone ?></td>
                                </tr>
                                <tr>
                                    <th>接收短信手机号</th>
                                    <?php
                                    $notice->phone=$userParent->mother_phone;
                                    ?>
                                    <td><?=$form->field($notice,'phone')->textInput()->label(false)?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <?= Html::submitButton('发送', ['class' =>'btn btn-success']) ?>
                            </div>
                        </div>
                    </div>
                    <?php \yii\widgets\ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">召回记录：</h3>
            </div>
            <div class="box-body no-padding">
                <table id="example2" class="table table-bordered table-hover"  style="font-size: 12px;">
                    <thead>
                    <tr>
                        <th>发送内容</th>
                        <th>发送时间</th>
                        <th>发送状态</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($notices as $k=>$v){
                        ?>
                        <tr>
                            <td><?=$v->content?></td>
                            <td><?=date('Y-m-d H:i:s',$v->createtime)?></td>
                            <td><?=\common\models\UserNotice::$stateText[$v->state]?></td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>