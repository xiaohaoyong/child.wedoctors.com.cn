<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\AppointSearchModels */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appoint-index">
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
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,

                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [

                                    'attribute' => '儿童姓名',
                                    'value' => function ($e) {
                                        return \common\models\ChildInfo::findOne(['id' => $e->childid])->name;
                                    }
                                ],
                                [

                                    'attribute' => '儿童性别',
                                    'value' => function ($e) {
                                        $child = \common\models\ChildInfo::findOne(['id' => $e->childid]);
                                        return \common\models\ChildInfo::$genderText[$child->gender];
                                    }
                                ],
                                [

                                    'attribute' => '儿童生日',
                                    'value' => function ($e) {
                                        $child = \common\models\ChildInfo::findOne(['id' => $e->childid]);
                                        return date('Y-m-d', $child->birthday);
                                    }
                                ],
                                [

                                    'attribute' => '母亲姓名',
                                    'value' => function ($e) {
                                        return \common\models\UserParent::findOne(['userid' => $e->userid])->mother;
                                    }
                                ],
                                [

                                    'attribute' => '户籍地',
                                    'value' => function ($e) {
                                        return \common\models\UserParent::findOne(['userid' => $e->userid])->field44;
                                    }
                                ],
                                [

                                    'attribute' => 'appoint_date',
                                    'value' => function ($e) {
                                        return date('Y-m-d', $e->appoint_date);
                                    }
                                ],
                                [

                                    'attribute' => 'appoint_time',
                                    'value' => function ($e) {
                                        return \common\models\Appoint::$timeText[$e->appoint_time];
                                    }
                                ],
                                'phone',
                                [

                                    'attribute' => 'state',
                                    'value' => function ($e) {
                                        return \common\models\Appoint::$stateText[$e->state];
                                    }
                                ],
                                [

                                    'attribute' => 'type',
                                    'value' => function ($e) {
                                        return \common\models\Appoint::$typeText[$e->type];
                                    }
                                ],
                                [

                                    'attribute' => 'cancel_type',
                                    'value' => function ($e) {
                                        return \common\models\Appoint::$cancel_typeText[$e->cancel_type];
                                    }
                                ],
                                [

                                    'attribute' => 'push_state',
                                    'value' => function ($e) {
                                        return \common\models\Appoint::$push_stateText[$e->push_state];
                                    }
                                ],
                                [

                                    'attribute' => 'mode',
                                    'value' => function ($e) {
                                        return \common\models\Appoint::$modeText[$e->mode];
                                    }
                                ],
                                [
                                    'attribute' => '序号',
                                    'value' => function ($e) {

                                        $index = \common\models\Appoint::find()
                                            ->andWhere(['appoint_date' => $e->appoint_date])
                                            ->andWhere(['<', 'id', $e->id])
                                            ->andWhere(['doctorid' => $e->doctorid])
                                            ->andWhere(['appoint_time' => $e->appoint_time])
                                            ->andWhere(['type' => $e->type])
                                            ->count();
                                        return $e->appoint_time . "-" . ($index + 1);
                                    }
                                ],
                                [
                                    'class' => 'common\components\grid\ActionColumn',
                                    'template' => '
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>{true}</li>
                                </ul>
                            </div>
                            ',
                                    'buttons' => [
                                        'true' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-database"></span> 完成', \yii\helpers\Url::to(['appoint/done', 'id' => $model->id]), ['data-confirm' => "是否确定已完成"]);
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