<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserDoctorAppoint */
/* @var $form yii\widgets\ActiveForm */

$this->title ='发送预约通知';

?>

<div class="user-doctor-appoint-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin();
                $child=\common\models\ChildInfo::findOne($childid);
                $userParent=\common\models\UserParent::findOne(['userid'=>$child->userid]);
                $userLogin=\common\models\UserLogin::find()->where(['userid'=>$userParent])->andWhere(['>','phone',0])->orderBy('openid desc')->one();
                $user=\common\models\User::findOne($userParent->userid);
                $phone=$userLogin->phone?$userLogin->phone:$userParent->mother_phone;
                $model->phone=$phone;
                $model->childid=$childid;
                $model->loginid=$userLogin?$userLogin->id:0;
                $model->userid=$userLogin->userid;
                ?>
                <table id="w0" class="table table-striped table-bordered detail-view">
                    <tbody>
                    <tr><th>儿童姓名</th><td><?=$child->name?>
                            <?=$form->field($model,'childid')->hiddenInput()->label(false)?>
                            <?=$form->field($model,'loginid')->hiddenInput()->label(false)?>
                            <?=$form->field($model,'userid')->hiddenInput()->label(false)?>

                        </td></tr>
                    <tr><th>母亲姓名</th><td><?=$userParent->mother?></td></tr>
                    <tr><th>联系电话</th><td><?=$phone?>
                        <?=$form->field($model,'phone')->hiddenInput()->label(false)?>
                        </td></tr>

                    <tr><th>日期</th><td><?= $form->field($model, 'date')->widget(\kartik\date\DatePicker::className(),['pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                            ]])?></td></tr>
                    <tr><th>时间段</th><td><?= $form->field($model, 'appoint_time')->dropDownList(\common\models\Appoint::$timeText, ['prompt'=>'请选择']) ?></td></tr>
                    <tr><th>类型</th><td><?= $form->field($model, 'type')->dropDownList(\common\models\UserDoctorAppoint::$typeText, ['prompt'=>'请选择']) ?></td></tr>
                    <tr><th>备注</th><td><?= $form->field($model, 'remark')->textarea() ?></td></tr>
                    </tbody></table>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
