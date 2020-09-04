<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\AppointCalling */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="login-box" style="width: 90%">

    <div class="appoint-index">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header" style="width: 200px;">
                    <?php
                    if($model->updatetime){
                       echo Html::a('暂停接诊', \yii\helpers\Url::to(['done', 'type' => 0]), ['class' => 'btn btn-block btn-danger']);
                    }else{
                        echo Html::a('开始接诊', \yii\helpers\Url::to(['done', 'type' => 1]), ['class' => 'btn btn-block btn-success']);
                    }
                    ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <?= GridView::widget([
                                'options' => ['class' => 'col-sm-12'],
                                'dataProvider' => $dataProvider,

                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [

                                        'attribute' => '姓名',
                                        'value' => function ($e) {
                                            if ($e->type == 4 || $e->type == 7) {
                                                return \common\models\AppointAdult::findOne(['userid' => $e->userid])->name;

                                            } elseif ($e->type == 5 || $e->type == 6) {
                                                return \common\models\Pregnancy::findOne(['id' => $e->childid])->field1;
                                            } else {
                                                return \common\models\ChildInfo::findOne(['id' => $e->childid])->name;
                                            }
                                        }
                                    ],
                                    [

                                        'attribute' => 'type',
                                        'format' => 'raw',
                                        'value' => function ($e) {
                                            if ($e->type == 1) {
                                                $class = 'label label-success';
                                            } elseif ($e->type == 2) {
                                                $class = 'label label-primary';
                                            } elseif ($e->type == 4) {
                                                $class = 'label label-danger';
                                            } elseif ($e->type == 5) {
                                                $class = 'label label-warning';
                                            } elseif ($e->type == 6) {
                                                $class = 'label label-info';
                                            }
                                            return '<span class="' . $class . '">' . \common\models\Appoint::$typeText[$e->type] . '</span>';
                                        }
                                    ],

                                    [

                                        'attribute' => 'appoint_time',
                                        'value' => function ($e) {
                                            return \common\models\Appoint::$timeText[$e->appoint_time];
                                        }
                                    ],
                                    [

                                        'attribute' => 'state',
                                        'value' => function ($e) {
                                            return \common\models\Appoint::$stateText[$e->state];
                                        }
                                    ],


                                    [
                                        'class' => 'yii\grid\Column',
                                        'contentOptions' => [
                                            'width' => '100',
                                        ],
                                        'header' => '预约疫苗',
                                        'content' => function ($e, $key, $index, $column) {
                                            return $e->vaccine == -2 ? "两癌筛查" : \common\models\Vaccine::findOne($e->vaccine)->name;

                                        }
                                    ],
                                    [

                                        'attribute' => '操作',
                                        'format' => 'raw',
                                        'value' => function ($e, $key, $index) {
                                            $a = Html::a('完成', \yii\helpers\Url::to(['state', 'id' => $e->id, 'state' => 3]), ['class' => 'btn btn-block btn-success', 'data-confirm' => "是否确定已完成"]);
                                            $b = Html::a('提醒', \yii\helpers\Url::to(['state', 'id' => $e->id, 'state' => 4]), ['class' => 'btn btn-block btn-warning', 'data-confirm' => "是否确定提醒就诊"]);
                                            $c = Html::a('跳过', \yii\helpers\Url::to(['state', 'id' => $e->id, 'state' => 2]), ['class' => 'btn btn-block btn-danger', 'data-confirm' => "是否确定跳过此条记录"]);
                                            $d = Html::a('恢复', \yii\helpers\Url::to(['state', 'id' => $e->id, 'state' => 1]), ['class' => 'btn btn-block btn-danger', 'data-confirm' => "是否确定恢复此条记录"]);

                                            $appointCallingList = \common\models\AppointCallingList::findOne(['aid' => $e->id]);
                                            if ($appointCallingList->state == 1 && $index == 0) {
                                                return $a.$b.$c;
                                            }elseif($appointCallingList->state==2){
                                                return $d;
                                            }elseif($appointCallingList->state==3){
                                                return "已完成";

                                            }
                                        }
                                    ],
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>