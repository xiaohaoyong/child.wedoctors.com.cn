<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HospitalAppoint */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospital-appoint-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">

                <?php $form = ActiveForm::begin(); ?>
                <table id="w0" class="table table-striped table-bordered detail-view">
                    <tbody>
                    <tr>
                        <th>周期长度</th>
                        <td><?= $form->field($model, 'cycle', ['options' => ['class' => "col-xs-3"]])->dropDownList(\common\models\UserDoctorAppoint::$cycleText, ['prompt' => '请选择'])->label(false) ?></td>
                    </tr>
                    <tr>
                        <th>
                            延迟日期<br>
                            注：0为次日可预约，1为后天以此类推
                        </th>
                        <td><?= $form->field($model, 'delay', ['options' => ['class' => "col-xs-3"]])->textInput()->label(false) ?>
                            天
                        </td>
                    </tr>
                    <tr><th>门诊时间</th><td><?= $form->field($model, 'week')->checkboxList([
                                '1' => '周一  ',
                                '2' => '周二  ',
                                '3' => '周三  ',
                                '4' => '周四  ',
                                '5' => '周五  '
                            ],['class'=>'flat-red'])->label(false) ?></td></tr>
                    </tbody>
                </table>
                <table id="w0" class="table table-striped table-bordered detail-view">
                    <tbody>
                    <tr>
                        <td></td>
                        <td>星期一</td>
                        <td>星期二</td>
                        <td>星期三</td>
                        <td>星期四</td>
                        <td>星期五</td>
                    </tr>
                    <?php
                    foreach(\common\models\HospitalAppointWeek::$typeText as $k=>$v){
                    ?>
                    <tr>
                        <td><?=$v?></td>
                        <td><?=Html::textInput('num[1]['.$k.']',$nums[1][$k]?$nums[1][$k]:0,['style'=>'text-align:center;'])?></td>
                        <td><?=Html::textInput('num[2]['.$k.']',$nums[2][$k]?$nums[2][$k]:0,['style'=>'text-align:center;'])?></td>
                        <td><?=Html::textInput('num[3]['.$k.']',$nums[3][$k]?$nums[3][$k]:0,['style'=>'text-align:center;'])?></td>
                        <td><?=Html::textInput('num[4]['.$k.']',$nums[4][$k]?$nums[4][$k]:0,['style'=>'text-align:center;'])?></td>
                        <td><?=Html::textInput('num[5]['.$k.']',$nums[5][$k]?$nums[5][$k]:0,['style'=>'text-align:center;'])?></td>
                    </tr>
                    <?php }?>
                    </tbody>
                </table>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
