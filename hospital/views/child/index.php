<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ChildInfoSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '全部已签约儿童';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="child-info-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?php

                        $columns = [

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
                                'attribute' => '父母',
                                'value' => function ($e) {
                                    return $e->parent->mother || $e->parent->father ? $e->parent->mother . "/" . $e->parent->father : "无";
                                }
                            ],
                            [

                                'attribute' => '联系电话',
                                'format' => 'html',
                                'value' => function ($e) {
                                    $data[] = $e->parent->mother . ":" . $e->parent->mother_phone;
                                    $data[] = $e->parent->father . ":" . $e->parent->father_phone;

                                    return implode("<br>", $data);
                                },
                                'contentOptions' => ['style' => 'word-break:keep-all'],
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
                            'fieldu47',
                            [
                                'attribute' => '现住址',
                                'value' => function ($e) {
                                    if ($e->parent->fieldu46) {
                                        return $e->parent->fieldu46;
                                    } elseif ($e->parent->fieldp47) {
                                        return $e->parent->fieldp47;
                                    } else {
                                        return '无';
                                    }
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
                                'class' => 'common\components\grid\ActionColumn',
                                'template' => '
                <div class="btn-group dropup">
                    <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>{appointPush}</li>
                    </ul>
                </div>',
                                'buttons' => [
                                    'appointPush' => function ($url, $model, $key) {
                                        return Html::a('<span class="fa fa-database"></span> 加号', \yii\helpers\Url::to(['appoint/push', 'childid' => $model->id]));
                                    },
                                ],
                            ],
                        ];

                        if ($q) {
                            $rs = array_pop($columns);
                            array_push($columns, $q);
                            array_push($columns, $rs);

                        }
                        ?>
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12', 'style' => "font-size: 12px;"],
                            'dataProvider' => $dataProvider,
                            'columns' => $columns,
                        ]); ?>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
