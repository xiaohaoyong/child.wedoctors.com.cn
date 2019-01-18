<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\UserDoctorAppointSearchModels */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '同步已签约数据';
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
                                    <th>身份证号码</th>
                                    <td>查询母亲姓名</td>
                                </tr>
                                <tr>
                                    <th><?=Html::textarea('idx','',['rows'=>30,'cols'=>50,'placeholder'=>
                                            "110101190000000045
110101190000000045
110101190000000045
110101190000000045
110101190000000045
110101190000000045
110101190000000045
110101190000000045
110101190000000045
110101190000000045
110101190000000045
110101190000000045
110101190000000045
"])?></th>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <?= Html::submitButton('上传', ['class' =>'btn btn-success']) ?>
                            </div>
                        </div>
                    </div>
                    <?php \yii\widgets\ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>