<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

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
                    <p>
                        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
                            'modelClass' => 'Countries',
                        ]), ['create'], ['class' => 'btn btn-success']) ?>
                    </p>
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

                                    'attribute' => '姓名',
                                    'value' => function ($e) {
                                        return $e->name();
                                    }
                                ],
                                'phone',
                                [

                                    'attribute' => 'type',
                                    'format'=>'raw',
                                    'value' => function ($e) {
                                        if($e->type==1){
                                            $class='label label-success';
                                        }elseif($e->type==2){
                                            $class='label label-primary';
                                        }elseif ($e->type==4){
                                            $class='label label-danger';
                                        }elseif ($e->type==5){
                                            $class='label label-warning';
                                        }else{
                                            $class='label label-info';
                                        }
                                        return '<span class="'.$class.'">'.\common\models\Appoint::$typeText[$e->type].'</span>';
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\Column',
                                    'contentOptions' => [
                                        'style'=>'word-break:break-all;word-wrap:break-word'
                                    ],
                                    'header' => '预约人其他信息',
                                    'content' => function ($e, $key, $index, $column){
                                        if($e->type==4 || $e->type==7 || $e->type==9){
                                            if($e->childid){
                                                $row=\common\models\AppointAdult::findOne(['id' => $e->childid]);
                                            }else {
                                                $row = \common\models\AppointAdult::findOne(['userid' => $e->userid]);
                                            }
                                            $html="姓名：".$row->name."<br>";
                                            $html.="性别：".\common\models\AppointAdult::$genderText[$row->gender]."<br>";
                                            $html.="手机：".$row->phone."<br>";

                                        }elseif($e->type==5 || $e->type==6){
                                            $preg=\common\models\Pregnancy::findOne(['id' => $e->childid]);
                                            $html="末次月经：".date('Ymd',$preg->field11)."<br>";
                                            $html.="证件号：".$preg->field4."<br>";
                                            $html.="户籍地：".\common\models\Pregnancy::$field90[$preg->field90]."<br>";
                                            $html.="孕妇户籍地：".\common\models\Area::$all[$preg->field7]."<br>";
                                            $html.="丈夫户籍地：".\common\models\Area::$all[$preg->field39]."<br>";
                                            $html.="现住址：".$preg->field10."<br>";
                                        }elseif($e->type==10){
                                            return "无";
                                        }elseif($e->type==11){
                                            if($e->childid){
                                                $row =  \common\models\AppointAdult::findOne(['id' => $e->childid]);
                                            }else {
                                                $row =  \common\models\AppointAdult::findOne(['userid' => $e->userid]);
                                            }
                                            $appoinOrder=\common\models\AppointOrder2::findOne(['aoid' => $row->id]);
                                            $html="";
                                            if($appoinOrder) {
                                                foreach ($appoinOrder->attributeLabels() as $k => $v) {
                                                    if ($k == 'type') {
                                                        $text = \common\models\AppointOrder2::$typeText[$appoinOrder->$k];
                                                    } elseif ($k == 'zhenduanText') {
                                                        $text = \common\models\AppointOrder2::$zhenduanText[$appoinOrder->$k];
                                                    } elseif ($k == 'field6') {
                                                        $text = \common\models\AppointOrder2::$field6Text[$appoinOrder->$k];
                                                    } elseif ($k == 'id' || $k == 'aoid') {
                                                        continue;
                                                    } else {
                                                        $text = $appoinOrder->$k;
                                                    }
                                                    $html .= $v . ": " . $text . "<br>";
                                                }
                                            }
                                        }else{
                                            $child= \common\models\ChildInfo::findOne(['id' => $e->childid]);
                                            $parent= \common\models\UserParent::findOne(['userid' => $e->userid]);
                                            $html="性别：".$child->name."<br>";
                                            $html.="生日：".date('Ymd',$child->birthday)."<br>";
                                            $html.="儿童户籍：".$child->fieldu47."<br>";
                                            $html.="母亲姓名：".$parent->mother."<br>";
                                            $html.="户籍地：".$parent->field44."<br>";
                                        }
                                        return $html;
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
                                [

                                    'attribute' => 'createtime',
                                    'value' => function ($e) {
                                        return date('Y-m-d', $e->createtime);
                                    }
                                ],
                                [

                                    'attribute' => 'state',
                                    'value' => function ($e) {
                                        return \common\models\Appoint::$stateText[$e->state];
                                    }
                                ],

                                [

                                    'attribute' => 'cancel_type',
                                    'value' => function ($e) {
                                        $cancel_typeText=\common\models\Appoint::$cancel_typeText+\common\models\Appoint::$hospital_cancel;
                                        return $cancel_typeText[$e->cancel_type];
                                    }
                                ],

                                [

                                    'attribute' => 'mode',
                                    'value' => function ($e) {
                                        return \common\models\Appoint::$modeText[$e->mode];
                                    }
                                ],

                                [
                                    'class' => 'yii\grid\Column',
                                    'contentOptions' => [
                                        'width'=>'100',
                                    ],
                                    'header' => '预约疫苗',
                                    'content' => function ($e, $key, $index, $column){
                                        return $e->vaccine==-2?"两癌筛查":\common\models\Vaccine::findOne($e->vaccine)->name;

                                    }
                                ],
                                [

                                    'attribute' => 'street',
                                    'value' => function ($e) {
                                        return \common\models\Street::findOne([$e->street])->title;
                                    }
                                ],
                                [
                                    'attribute' => '排号顺序',
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
                                    <li>{true}{image}</li>
                                </ul>
                            </div>
                            ',
                                    'buttons' => [
                                        'true' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-database"></span> 完成', \yii\helpers\Url::to(['appoint/done', 'id' => $model->id]), ['data-confirm' => "是否确定已完成"]);
                                        },
                                        'image' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-database"></span> 查看凭证', \yii\helpers\Url::to(['appoint/view', 'id' => $model->id]));
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