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
</div>