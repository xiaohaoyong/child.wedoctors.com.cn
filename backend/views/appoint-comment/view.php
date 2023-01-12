<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Appoint */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Appoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '列表', 'url' => ['index']],
];

?>
<div class="appoint-view">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <p>
                   基本信息：
                </p>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        [
                            'attribute' => '姓名',
                            'value' => function ($e) {
                                $info = \common\models\ChildInfo::findOne(['userid' => $e->userid]);
                                return $info->name;
                            }
                        ],
                        [
                            'attribute' => 'Doctor Id',
                            'value' => function ($e) {
                                $doctor=\common\models\UserDoctor::find()->where(['userid'=>$e->doctorid])->one();
                                return $doctor->name;
                            }
                        ],
                        [
                            'attribute' => '预约时间',
                            'value' => function ($e) {
                                $info = \common\models\Appoint::findOne(['id' => $e->aid]);
                                return date("Y-m-d H:i:s",$info->appoint_time);
                            }
                        ],
                        [
                            'attribute' => '预约项目',
                            'value' => function ($e) {
                                $info = \common\models\Appoint::findOne(['id' => $e->aid]);
                                return \common\models\Appoint::$typeText[$info->type];
                            }
                        ],
                        [
                            'attribute' => '预约疫苗',
                            'value' => function ($e) {
                                if($e->is_envir){
                                    $info = \common\models\Appoint::findOne(['id' => $e->aid]);
                                    $infos = \common\models\Vaccine::findOne(['id'=>$info->vaccine]);
                                    return $infos->name;
                                }
                            }
                        ],
                        [
                            'attribute' => '就诊状态',
                            'value' => function ($e) {
                                return '已完成';
                            }
                        ],
                    ],
                ]) ?>

            </div>


            <div class="box-body">
                <p>
                    用户预约就诊后评价：
                </p>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label' => '评价时间',
                            'value' => function ($e){
                                return date("Y-m-d H:i:s",$e->createtime);
                            }
                        ],
                        [
                            'label' => '整体评价',
                            'value' => function ($e){
                                if($e->is_rate==1){
                                    return '好评';
                                }elseif($e->is_rate==2){
                                    return '中评';
                                }elseif($e->is_rate==3){
                                    return '差评';
                                }
                            }
                        ],
                        [
                            'attribute' => '医院环境满意度',
                            'value' => function ($e) {
                                $rs=$e->is_envir."星 ";
                                if($e->is_envir<4){
                                    if($e->is_envir_on){
                                        $env_r=explode(",",$e->is_envir_on);
                                        $rsd=" 原因：";
                                        foreach ($env_r as $e_v){
                                            $rsd .= \common\models\AppointComment::$envirArr[$e_v].", ";
                                        }
                                    }
                                }
                                return $rs.$rsd;
                            }
                        ],
                        [
                            'attribute' => '就诊流程满意度',
                            'value' => function ($e) {
                                $rs=$e->is_process."星 ";
                                if($e->is_process<4){
                                    if($e->is_process_on){
                                        $pro_r=explode(",",$e->is_process_on);
                                        $rsd=" 原因：";
                                        foreach ($pro_r as $e_v){
                                            $rsd .= \common\models\AppointComment::$processArr[$e_v].", ";
                                        }
                                    }
                                }
                                return $rs.$rsd;
                            }
                        ],
                        [
                            'attribute' => '医院态度满意度',
                            'value' => function ($e) {
                                $rs=$e->is_staff."星 ";
                                if($e->is_staff<4){
                                    if($e->is_staff_on){
                                        $sta_r=explode(",",$e->is_staff_on);
                                        $rsd=" 原因：";
                                        foreach ($sta_r as $e_v){
                                            $rsd .= \common\models\AppointComment::$staffArr[$e_v].", ";
                                        }
                                    }
                                }
                                return $rs.$rsd;
                            }
                        ],

                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>