<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\ChildHealthRecordSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '儿童健康档案';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="child-health-record-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options'=>['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['attribute' => 'childid', 'value' => function ($e) {
                                    return \common\models\ChildInfo::findOne($e->childid)->name;
                                }

                                ], ['attribute' => 'userid', 'value' => function ($e) {
                                    return \hospital\models\user\UserDoctor::findOne([$e->userid])->name;
                                }

                                ], 'content', ['attribute' => 'createtime', 'format' => ['date', 'php:Y-m-d H:i:s']]


                            ]
                        ]); ?>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
