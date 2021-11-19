<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/11/3
 * Time: 10:26 PM
 */
use yii\helpers\Html;

$this->title="信息提交";
?>
<style>
    body {
        background-color: #fd898a
    }

    .form {
        margin: 50px 20px;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 20px;
    }
</style>
<div class="form">
    <?php $form = \yii\widgets\ActiveForm::begin(['action' => ['userid' => $doctor['userid']]]); ?>


    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder'=>'请输入身份证上的真实姓名']) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true,'placeholder'=>'请输入您的手机号']) ?>
    <div style="display: flex;">
        <?= $form->field($model,'province')->dropDownList(\common\models\Area::$province,
            [
                'prompt'=>'请选择',
                'onchange'=>'
            $("#'.Html::getInputId($model,'city').'").html(\''.Html::tag('option',Html::encode("请选择"),array('value'=>0)).'\');
            $("#'.Html::getInputId($model,'county').'").html(\''.Html::tag('option',Html::encode("请选择"),array('value'=>0)).'\');
            $.post("'.\yii\helpers\Url::to(['mother-form/area']).'?id="+$(this).val(),function(data){
                $("#'.Html::getInputId($model,'city').'").html(data);
            });',
            ]) ?>
        <?php $city=\common\models\Area::$city[$model->province]?\common\models\Area::$city[$model->province]:[];?>
        <?= $form->field($model,'city')->dropDownList($city,
            [
                'prompt'=>'请选择',
                'onchange'=>'
            $("#'.Html::getInputId($model,'county').'").html(\''.Html::tag('option',Html::encode("请选择"),array('value'=>0)).'\');
            $.post("'.\yii\helpers\Url::to(['mother-form/area']).'?type=county&id="+$(this).val(),function(data){
                $("#'.Html::getInputId($model,'county').'").html(data);
            });',
            ]) ?>
        <?php $county=\common\models\Area::$city[$model->province]?\common\models\Area::$county[$model->city]:[];?>

        <?= $form->field($model,'county')->dropDownList($county,['prompt'=>'请选择']) ?>
    </div>
    <?= $form->field($model, 'address')->textarea(['placeholder'=>'详细地址：街道，小区，门牌号']) ?>
    <?= $form->field($model, 'idcard')->textInput(['maxlength' => true,'placeholder'=>'请输入身份证号后8位']) ?>
    <?php
    for ($i = 0; $i <= 120; $i++) {
        $year[date('Y', strtotime("-$i year"))] = date('Y', strtotime("-$i year"));
    }
    $month[] = '<option value="">--月--</option>';
    foreach (range(1, 12) as $val) {
        $month[] = '<option value="' . $val . '">' . $val . '</option>';
    }
    $dayNum = [29, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    echo "<script> var days=Array;";
    foreach ($dayNum as $k => $val) {
        echo "days[" . ($k) . "]='<option value=\"\">--日--</option>';";
        for ($i = 1; $i <= $val; $i++) {
            echo "days[" . ($k) . "]+=\"<option value='" . "$i" . "'>" . $i . '</option>";';
        }
    }
    echo "</script>";


    ?>
    <div class="form-group field-motherform-province">
        <label class="control-label">孕妈预产期/宝宝出生日期</label>
    </div>
    <div style="display: flex;">

        <?= $form->field($model, 'year')->dropDownList($year,
            [
                'prompt' => '--年--',
                'onchange' => "
                            window.year= $(this).val();
                            $('#motherform-month').html('" . implode('', $month) . "');
                            $('#motherform-day').html('<option value=\"\">--日--</option>');

                        ",
            ])->label(false) ?>
        <?= $form->field($model, 'month')->dropDownList([],
            [
                'prompt' => '--月--',
                'onchange' => "
                            window.month= $(this).val();
                            var montha=month;
                            console.log(year);
                            if (month==2 && (year % 4 == 0 && year % 100 != 0 || year % 400 == 0)) {
                                montha = 0;
                            }
                            $('#motherform-day').html(days[montha]);

                        ",
            ])->label(false) ?>
        <?= $form->field($model, 'day')->dropDownList([],
            [
                'prompt' => '--日--',
                'onchange' => "
                            var day=$(this).val();
                            $('#motherform-date').val(year+'-'+month+'-'+day);
                        "
            ])->label(false) ?>
    </div>
    <?= $form->field($model, 'date')->hiddenInput()->label(false) ?>

    <div class="form-group field-motherform-province">
        <label class="control-label">证件照片（身份证+证明照）</label>
        <div style="color:#9f191f">

            1、身份证照：请提交孕妈/宝妈本人身份证照片， 您可以将个人隐私信息进行模糊，但需保留姓名、 身份证号码后八位；<br>
            2、证明照：请提交可证明身份证照是孕妈/宝妈本 人的相关证件照片，如：出生医学证明、超声检查 报告等，您可以将个人隐私信息进行模糊，但须保 留宝妈姓名、孕周或宝宝出生日期。
        </div>
    </div>

    <?= $form->field($model, 'userid')->hiddenInput(['value'=>$userid])->label(false) ?>

    <?= $form->field($model, 'img')->fileInput()->label(false) ?>

    <?= \yii\bootstrap\Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-block btn-danger btn-lg' : 'btn btn-primary']) ?>

    <?php \yii\widgets\ActiveForm::end() ?>
</div>