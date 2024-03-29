<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ChildInfoSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管辖儿童';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="child-info-index">
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
                        <?php
                        $columns=[

                            'name',
                            [

                                'attribute' => '联系电话',
                                'value' => function ($e) {
                                    return \common\models\User::findOne($e->userid)->phone;
                                }
                            ],
                            [

                                'attribute' => '性别',
                                'value' => function ($e) {
                                    return \common\models\ChildInfo::$genderText[$e->gender];
                                }
                            ],
                            [
                                'attribute' => '年龄',
                                'value' => function ($e) {
                                    $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $e->birthday));
                                    if ($DiffDate[0]) {
                                        $age = $DiffDate[0] . "岁";
                                    } elseif ($DiffDate[1]) {
                                        $age = $DiffDate[1] . "月";
                                    } else {
                                        $age = $DiffDate[2] . "天";
                                    }
                                    return $age;
                                }
                            ],
                            [
                                'attribute' => '父母',
                                'value' => function ($e) {
                                    return $e->parent->mother || $e->parent->father ? $e->parent->mother . "/" . $e->parent->father : "无";
                                }
                            ],
                            [
                                'attribute' => '母亲电话',
                                'value' => function ($e) {
                                    return $e->parent->mother_phone ? $e->parent->mother_phone : "无";
                                }
                            ],
                            [
                                'attribute' => '父亲电话',
                                'value' => function ($e) {
                                    return $e->parent->father_phone ? $e->parent->father_phone : "无";
                                }
                            ],
                            [
                                'attribute' => '联系人姓名',
                                'value' => function ($e) {
                                    return $e->parent->field11 ? $e->parent->field11 : "无";
                                }
                            ],
                            [
                                'attribute' => '联系人电话',
                                'value' => function ($e) {
                                    return $e->parent->field12 ? $e->parent->field12 : "无";
                                }
                            ],
                            [
                                'attribute' => '签约社区',
                                'format' => 'raw',
                                'value' => function ($e) {
                                    $sign = \common\models\DoctorParent::findOne(['parentid' => $e->userid, 'level' => 1]);

                                    $file3 = "--";
                                    if ($sign->level == 1) {
                                        $doctorParent = \common\models\DoctorParent::findOne(['parentid' => $e->userid, 'level' => 1]);
                                        $doctor = \common\models\UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
                                        $file3 = $doctor ? $doctor->name : "==";
                                    }

                                    return $file3;

                                }
                            ],
                            'field50',
                            [
                                'attribute' => '现住址',
                                'value' => function ($e) {
                                    return $e->parent->fieldu46 ? $e->parent->fieldu46 : "无";
                                }
                            ],
                            [
                                'attribute' => '签约时间',
                                'value' => function ($e) {
                                    $sign = \common\models\DoctorParent::findOne(['parentid' => $e->userid, 'level' => 1]);

                                    return $sign->level == 1 ? date('Y-m-d H:i', $sign->createtime) : "无";
                                }
                            ],
                            [
                                'attribute' => '签约状态',
                                'value' => function ($e) {

                                    $sign = \common\models\DoctorParent::findOne(['parentid' => $e->userid, 'level' => 1]);

                                    if ($sign->level != 1) {
                                        $return = "未签约";
                                    } else {
                                        if ($e->source <= 38) {
                                            $return = "已签约未关联";

                                        } else {
                                            $return = "已签约";
                                        }
                                    }
                                    return $return;
                                }
                            ],
                            [
                                'class' => 'common\components\grid\ActionColumn',
                                'template' => '
                <div class="btn-group dropup">
                    <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-settings"></i> 记录 <i class="fa fa-angle-up"></i></a>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>{articleuser} </li><li>{childhealthrecord}</li><li>{download}</li><li>{appointPush}</li><li>{notice}</li><li>{exaView}</li>
                    </ul>
                </div>',
                                'buttons' => [
                                    'articleuser' => function ($url, $model, $key) {
                                        return Html::a('<span class="fa fa-database"></span> 宣教记录', \yii\helpers\Url::to(['article-user/index', 'ArticleUserSearchModel[childid]' => $model->id]));
                                    },
                                    'childhealthrecord' => function ($url, $model, $key) {
                                        return Html::a('<span class="fa fa-database"></span> 健康档案', \yii\helpers\Url::to(['child-info/view', 'id' => $model->id]));
                                    },
                                    'download' => function ($url, $model, $key) {
                                        return Html::a('<span class="fa fa-database"></span> 下载宣教记录', \yii\helpers\Url::to(['article-user/download', 'childid' => $model->id]));
                                    },
                                    'appointPush' => function ($url, $model, $key) {
                                        return Html::a('<span class="fa fa-database"></span> 发送预约通知', \yii\helpers\Url::to(['appoint/push', 'childid' => $model->id]));
                                    },
                                    'notice' => function ($url, $model, $key) {
                                        $sign = \common\models\DoctorParent::findOne(['parentid' => $model->userid, 'level' => 1]);
                                        if($sign){
                                            return Html::a('<span class="fa fa-database"></span> 发送召回通知',"#");
                                        }else {
                                            return Html::a('<span class="fa fa-database"></span> 发送召回通知', \yii\helpers\Url::to(['notice/recall', 'userid' => $model->userid, 'childid' => $model->id]));
                                        }
                                    },
                                    'exaView' => function ($url, $model, $key) {
                                        return Html::a('<span class="fa fa-database"></span> 体检详情', \yii\helpers\Url::to(['examination/index', 'ExaminationModels[childid]' => $model->id]));
                                    },
                                ],
                            ],
                        ];

                        if($q) {
                            $rs=array_pop($columns);
                            array_push($columns,$q);
                            array_push($columns,$rs);

                        }
                        ?>
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12', 'style' => "font-size: 12px;"],
                            'dataProvider' => $dataProvider,
                            'columns' =>$columns,
                        ]); ?>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
