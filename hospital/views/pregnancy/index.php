<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\PregnancySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="pregnancy-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>

                    dsdsf
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12 table text-nowrap'],
                            'dataProvider' => $dataProvider,

                            'columns' => [
                                [
                                    'attribute' => 'field5',
                                    'value' => function ($e) {
                                        return $e->field2 ? date('Y-m-d', $e->field5) : '';
                                    }
                                ],
                                'field1',
                                [
                                    'attribute' => 'field2',
                                    'value' => function ($e) {
                                        return $e->field2 ? date('Y-m-d', $e->field2) : '';
                                    }
                                ],
                                'field4',
                                'field6',
                                [
                                    'attribute' => 'field16',
                                    'value' => function ($e) {
                                        return $e->field2 ? date('Y-m-d', $e->field16) : '';
                                    }
                                ],
                                [
                                    'attribute' => 'field15',
                                    'value' => function ($e) {
                                        return $e->field2 ? date('Y-m-d', $e->field15) : '';
                                    }
                                ],

                                'field13',
                                'field17',

                                'field36',
                                'field37',
                                'field38',

                                [
                                    'attribute' => 'field49',
                                    'value' => function ($e) {
                                        return \common\models\Pregnancy::$field49[$e->field49];
                                    }
                                ],
                                [
                                    'attribute' => 'field61',
                                    'value' => function ($e) {
                                        return $e->field2 ? date('Y-m-d', $e->field61) : '';
                                    }
                                ],
                                'field62',
                                [
                                    'attribute' => '签约社区',
                                    'value' => function ($e) {
                                        if ($e->familyid) {
                                            $doctorParent = \common\models\DoctorParent::findOne(['parentid' => $e->familyid]);
                                            if ($doctorParent && $doctorParent->doctorid) {
                                                $doctor = \common\models\UserDoctor::findOne(['userid' => $doctorParent->doctorid]);
                                            }
                                        }
                                        return $doctor ? $doctor->name : '';
                                    }
                                ],
                                [
                                    'attribute' => '签约时间',
                                    'value' => function ($e) {
                                        if ($e->familyid) {
                                            $doctorParent = \common\models\DoctorParent::findOne(['parentid' => $e->familyid]);
                                        }
                                        return $doctorParent ? date('Y-m-d',$doctorParent->createtime) : '';
                                    }
                                ],
                                [
                                    'attribute' => '签约状态',
                                    'value' => function ($e) {
                                        if ($e->familyid) {
                                            $doctorParent = \common\models\DoctorParent::findOne(['parentid' => $e->familyid]);
                                        }
                                        return $doctorParent ? '已签约' : '未签约';
                                    }
                                ],
                                [
                                    'attribute' => '签约协议',
                                    'value' => function ($e) {
                                        if ($e->familyid) {
                                            $auto=\common\models\Autograph::findOne(['userid'=>$e->familyid]);
                                        }
                                        return $auto ? '已签字' : '未签字';                                    }
                                ],

                                [
                                    'class' => 'common\components\grid\ActionColumn',
                                    'template' => '
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                                     <li>{show} </li>
                                </ul>
                            </div>
                            ', 'buttons' => [
                                    'show' => function ($url, $model, $key) {
                                        return Html::a('<span class="fa fa-database"></span> 查看追访记录', \yii\helpers\Url::to(['interview/index', 'InterviewSearch[userid]' => $model->familyid]));
                                    },
                                ],
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>