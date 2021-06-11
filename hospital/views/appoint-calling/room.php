<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dmstr\widgets\Alert;

$this->title = "诊室操作";
/* @var $this yii\web\View */
/* @var $model common\models\AppointCalling */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="login-box">

    <div class="appoint-calling-form">
        <div class="login-logo">
            <a href="#">诊室操作</a>
        </div>
        <div class="col-xs-12">
            <div class="box">
                <div class="box-title">
                    <?= Alert::widget() ?>


                    <div class="margin">
                        <div class="btn-group">
                            <?= Html::a('完成/开始', \yii\helpers\Url::to(['state', 'id' => $appointCallingList->id, 'state' => 3]), ['class' => 'btn btn-block btn-success', 'data-confirm' => "点击完成会将当前用户预约信息标记为完成，并且开始叫下一位患者！"]) ?>
                        </div>
                        <div class="btn-group">
                            <?= Html::a('提醒', \yii\helpers\Url::to(['state', 'id' => $appointCallingList->id, 'state' => 4]), ['class' => 'btn btn-block btn-warning', 'data-confirm' => "是否确定提醒就诊"]) ?>
                        </div>
                        <div class="btn-group">
                            <?= Html::a('跳过', \yii\helpers\Url::to(['state', 'id' => $appointCallingList->id, 'state' => 2]), ['class' => 'btn btn-block btn-danger', 'data-confirm' => "是否确定跳过此条记录"]) ?>
                        </div>

                    </div>
                    <div class="margin">
                        操作说明：<br>
                        1，完成：点击完成则开始呼叫下一位患者，并且会将之前患者的预约信息标记为已完成<br>
                        2，提醒：对排号患者发送提醒<br>
                        3，跳过，点击此操作用户排号将标记为失效，需要重新排队，并且同时呼叫下一位
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php if($appoint){?>
                    <table class="table table-striped table-bordered detail-view">
                        <tbody>
                        <tr>
                            <th>姓名</th>
                            <td><?=$appoint->name()?></td>
                        </tr>
                        <tr>
                            <th>预约人其他信息</th>
                            <td>
                                <?php
                                if($appoint->type==4||$appoint->type==7){
                                    $row=\common\models\AppointAdult::findOne(['userid' => $appoint->userid]);
                                    $html="姓名：".$row->name."<br>";
                                    $html.="性别：".\common\models\AppointAdult::$genderText[$row->gender]."<br>";
                                    $html.="手机：".$row->phone."<br>";

                                }elseif($appoint->type==5 || $appoint->type==6){
                                    $preg=\common\models\Pregnancy::findOne(['id' => $appoint->childid]);
                                    $html="末次月经：".date('Ymd',$preg->field11)."<br>";
                                    $html.="证件号：".$preg->field4."<br>";
                                    $html.="户籍地：".\common\models\Pregnancy::$field90[$preg->field90]."<br>";
                                    $html.="孕妇户籍地：".\common\models\Area::$all[$preg->field7]."<br>";
                                    $html.="丈夫户籍地：".\common\models\Area::$all[$preg->field39]."<br>";
                                    $html.="现住址：".$preg->field10."<br>";
                                }else{
                                    $child= \common\models\ChildInfo::findOne(['id' => $appoint->childid]);
                                    $parent= \common\models\UserParent::findOne(['userid' => $appoint->userid]);
                                    $html="性别：".$child->name."<br>";
                                    $html.="生日：".date('Ymd',$child->birthday)."<br>";
                                    $html.="儿童户籍：".$child->fieldu47."<br>";
                                    $html.="母亲姓名：".$parent->mother."<br>";
                                    $html.="户籍地：".$parent->field44."<br>";
                                }
                                echo

                                $html;

                                ?>

                            </td>
                        </tr>
                        <tr>
                            <th>预约日期</th>
                            <td><?=date('Y-m-d', $appoint->appoint_date)?></td>
                        </tr>
                        <tr>
                            <th>预约时间</th>
                            <td><?=\common\models\Appoint::$timeText[$appoint->appoint_time]?></td>
                        </tr>
                        <tr>
                            <th>预约状态</th>
                            <td><?=\common\models\Appoint::$stateText[$appoint->state]?></td>
                        </tr>

                        </tbody>
                    </table>
                    <?php }elseif($appointCallingList->aid==0){?>
                        <table class="table table-striped table-bordered detail-view">
                            <tbody>
                            <tr>
                                <th>姓名</th>
                                <td>临时</td>
                            </tr>
                            <tr>
                                <th>预约日期</th>
                                <td><?=date('Y-m-d')?></td>
                            </tr>
                            <tr>
                                <th>预约时间</th>
                                <td><?=\common\models\Appoint::$timeText[$appointCallingList->time]?></td>
                            </tr>
                            </tbody>
                        </table>

                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>