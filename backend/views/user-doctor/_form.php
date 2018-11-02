<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $userInfo common\models\UserDoctor */
/* @var $userLogin common\models\userLogin */

/* @var $form yii\widgets\ActiveForm */
/*
 * 'userid' => '用户ID',
            'name' => '姓名',
            'sex' => '性别',
            'age' => '年龄',
            'birthday' => '十个八个如',
            'phone' => '手机号码',
            'hospitalid' => '所以在医院',
            'subject_b' => '一级科室',
            'subject_s' => '二级科室',
            'title' => '职称',
            'intro' => '简介',
            'avatar' => '头像',
            'skilful' => '擅长',
            'idnum' => '身份证号码',
'province' => '省',
            'county' => '县',
            'city' => '市',
            'atitle' => '行政职称',
            'otype' => '职业类型',
 */


?>

<div class="users-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($userInfo, 'name')->textInput() ?>

                <?php
                if ($userInfo->avatar) {

                    ?>
                    <div class="field-userInfo-avatar required">
                        <label class="col-lg-3 control-label" for="userInfo-avatar">封面</label>
                        <?= Html::img($userInfo->avatar, ['width' => 300]) ?>
                        <div class="help-block"></div>
                    </div>
                <?php } ?>
                <?= $form->field($userInfo, 'avatar')->fileInput() ?>

                <?= $form->field($userInfo, 'sex')->radioList(\common\models\UserDoctor::$sexText, ['prompt' => '请选择']) ?>

                <?= $form->field($userInfo, 'age')->textInput() ?>


                <?= $form->field($model, 'phone')->textInput() ?>
                <?= $form->field($userInfo, 'phone')->textInput() ?>
                <?= $form->field($userInfo, 'appoints')->checkboxList([1=>'体检',2=>'疫苗',3=>'微量元素'], ['prompt' => '请选择']) ?>

                <?= $form->field($userInfo, 'longitude')->textInput() ?>
                <?= $form->field($userInfo, 'latitude')->textInput() ?>


                <?= $form->field($userInfo, 'subject_b')->dropDownList(\common\models\Subject::$subject_b,
                    [
                        'prompt' => '请选择',

                    ]) ?>

                <?= $form->field($userInfo, 'title')->dropDownList(\common\models\UserDoctor::$titleText, ['prompt' => '请选择']) ?>

                <?= $form->field($userInfo, 'intro')->textInput() ?>
                <?= $form->field($userInfo, 'skilful')->textInput() ?>

                <?php
                $options['prompt'] = '请选择';
                if (0 == 1) {

                    ?>
                    <?= $form->field($userInfo, 'hospitalid')->dropDownList([], ['disabled' => 'disabled', 'prompt' => \common\models\Hospital::findOne(Yii::$app->user->identity->hospital)->name]) ?>


                <?php } else {
                    ?>
                    <?= $form->field($userInfo, 'province')->dropDownList(\common\models\Area::$province,
                        [
                            'prompt' => '请选择',
                            'onchange' => '
            $("#' . Html::getInputId($userInfo, 'city') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $("#' . Html::getInputId($userInfo, 'county') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $.post("' . \yii\helpers\Url::to(['area/get']) . '?id="+$(this).val(),function(data){
                $("#' . Html::getInputId($userInfo, 'city') . '").html(data);
            });',
                        ]) ?>
                    <?php $city = \common\models\Area::$city[$userInfo->province] ? \common\models\Area::$city[$userInfo->province] : []; ?>
                    <?= $form->field($userInfo, 'city')->dropDownList($city,
                        [
                            'prompt' => '请选择',
                            'onchange' => '
            $("#' . Html::getInputId($userInfo, 'county') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $.post("' . \yii\helpers\Url::to(['area/get']) . '?type=county&id="+$(this).val(),function(data){
                $("#' . Html::getInputId($userInfo, 'county') . '").html(data);
            });',
                        ]) ?>
                    <?php $county = \common\models\Area::$city[$userInfo->province] ? \common\models\Area::$county[$userInfo->city] : []; ?>

                    <?= $form->field($userInfo, 'county')->dropDownList($county, [
                        'prompt' => '请选择',
                        'onchange' => '
            $("#' . Html::getInputId($userInfo, 'hospitalid') . '").html(\'' . Html::tag('option', Html::encode("请选择"), array('value' => 0)) . '\');
            $.post("' . \yii\helpers\Url::to(['hospital/get']) . '?HospitalSearchModel[county]="+$(this).val(),function(data){
                $("#' . Html::getInputId($userInfo, 'hospitalid') . '").html(data);
            });',
                    ]) ?>

                    <?= $form->field($userInfo, 'hospitalid')->dropDownList(\common\models\Hospital::find()->select('name')->indexBy('id')->where(['county' => $userInfo['county']])->column(), ['prompt' => '请选择']) ?>

                <?php } ?>

                <?php
                if ($userInfo->authimg) {

                    ?>
                    <div class="field-userInfo-authimg required">
                        <label class="col-lg-3 control-label" for="userInfo-authimg">证件照</label>
                        <?= Html::img($userInfo->authimg, ['width' => 300]) ?>
                        <div class="help-block"></div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>