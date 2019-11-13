<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ChildInfoSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '未体检儿童数据';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="child-info-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>
                    <?php echo $this->render('un_search', ['model' => $searchModel]); ?>
                    <p>注：未签约用户无法推送提醒消息</p>
                    <p>注：该列表仅显示了当前阶段还未进行体检的用户，过期未进行体检未显示</p>
                    <p>注：该列表依赖体检数据，请更新后使用</p>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?php
                        if(Yii::$app->user->identity->county==1105) {
                            $q = [
                                'attribute' => '签约协议',
                                'value' => function ($e) {
                                    $autograph = \common\models\Autograph::findOne(['userid' => $e->userid]);
                                    if ($autograph) {
                                        $return = "已签字";
                                    } else {
                                        $return = "未签字";
                                    }
                                    return $return;
                                }
                            ];
                        }
                        $columns=[

                            'name',

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
                                'attribute' => '出生日期',
                                'value' => function ($e) {
                                    return  date('Y-m-d', $e->birthday);
                                }
                            ],
                            [
                                'attribute' => '父母',
                                'value' => function ($e) {
                                    return $e->parent->mother || $e->parent->father ? $e->parent->mother . "/" . $e->parent->father : "无";
                                }
                            ],
                            [
                                'attribute' => '联系电话',
                                'format'=>'html',
                                'value' => function ($e) {
                                    $v='';
                                    $v.="母亲：".$e->parent->mother_phone ? $e->parent->mother_phone : "无";
                                    $v.="<br>";
                                    $v.="父亲：".$e->parent->father_phone ? $e->parent->father_phone : "无";
                                    return $v;

                                }
                            ],
                            [
                                'attribute' => '已体检日期',
                                'value' => function ($e) {

                            $c=\common\models\Examination::find()->select('field82')->where(['childid'=>$e->id])->column();
                                    return implode(',',$c);
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
                                'attribute' => '操作',
                                'format'=>'html',
                                'value' => function ($e) {
                                    return Html::a('发送提醒');

                                }
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
