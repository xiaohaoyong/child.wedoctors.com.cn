<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserDoctorAppoint */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-doctor-appoint-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model,'type')->hiddenInput()->label(false);?>
                <?= $form->field($model, 'doctorid')->hiddenInput()->label(false); ?>

                <table id="w0" class="table table-striped table-bordered detail-view">
                    <tbody>
                    <tr><th>允许预约日期</th><td><?= $form->field($model, 'week')->checkboxList([
                                '1' => '周一  ',
                                '2' => '周二  ',
                                '3' => '周三  ',
                                '4' => '周四  ',
                                '5' => '周五  '
                            ],['class'=>'flat-red'])->label(false) ?></td></tr>
                    <tr><th>周期长度</th><td><?= $form->field($model, 'cycle',['options'=>['class'=>"col-xs-3"]])->dropDownList(\common\models\HospitalAppoint::$cycleText, ['prompt'=>'请选择'])->label(false) ?></td></tr>
                    <tr><th>
                            延迟日期<br>
                            注：0为可可预约次日，1为后天以此类推
                        </th><td><?= $form->field($model, 'delay',['options'=>['class'=>"col-xs-3"]])->textInput()->label(false) ?> 天</td></tr>
                    <tr><th>08：00-09：00预约人数</th><td><?= $form->field($model, 'type1_num',['options'=>['class'=>"col-xs-3"]])->textInput()->label(false) ?> 人</td></tr>
                    <tr><th>09：00-10：00预约人数</th><td><?= $form->field($model, 'type2_num',['options'=>['class'=>"col-xs-3"]])->textInput()->label(false) ?> 人</td></tr>
                    <tr><th>10：00-11：00预约人数</th><td><?= $form->field($model, 'type3_num',['options'=>['class'=>"col-xs-3"]])->textInput()->label(false) ?> 人</td></tr>
                    <tr><th>13：00-14：00预约人数</th><td><?= $form->field($model, 'type4_num',['options'=>['class'=>"col-xs-3"]])->textInput()->label(false) ?> 人</td></tr>
                    <tr><th>14：00-15：00预约人数</th><td><?= $form->field($model, 'type5_num',['options'=>['class'=>"col-xs-3"]])->textInput()->label(false) ?> 人</td></tr>
                    <tr><th>15：00-16：00预约人数</th><td><?= $form->field($model, 'type6_num',['options'=>['class'=>"col-xs-3"]])->textInput()->label(false) ?> 人</td></tr></tbody></table>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
