<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\HealthRecords;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $model common\models\HealthRecords */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    body {
        background: #ffffff;
    }
</style>
<div class="health-records">
    <div class="header">
        <h2>家庭医生服务签约</h2>
        <div class="info">
            家长您好！
            <p>
                儿童健康关乎社会未来，每个人是自己健康第一责任人。从小养成健康生活方式和习惯，对于维护健康和疾病预防具有重要意义。
            </p>
            <p>
                为了更好提供新冠等传染病以及儿童常见病预防服务，做好在校学龄儿童健康管理等学校卫生服务，我校联合朝阳区八里庄社区卫生服务中心开展“家庭医生进校园”签约服务。
            </p>
            <p>
                疫情常态化防控期间，让我们共同做好学校卫生安全保障、做好学生健康维护工作，感谢各位家长的支持配合！
            </p>
            <p>
                请您仔细填写、核对以下信息，孩子个人信息我们将妥善保管：
            </p>

        </div>

    </div>
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal',
        ],
        'fieldConfig' => [  //统一修改字段的模板
            'template' => "<div class='form-group-info'>{label}\n<div class='form-input'>{input}</div></div>{error}",
            'inputOptions' => ['class' => ''],
        ],
    ]);
    ?>
    <div class="title">请选择服务包</div>
    <div class="form-group ">
        <div class="form-group-info" style="margin: 20px; display: flex;justify-content: space-between;">
            <label for="healthrecords-field29">“家庭医生签约进校园”学龄儿童服务包</label>
            <div>
                <?=Html::checkbox('HealthRecords[field44]',isset($model->field44)?$model->field44:true)?>

                <?php
                echo Html::a('详情', '#', [
                    'data-target' => '#jt-modal' ,//关联模拟框(模拟框的ID)
                    'data-toggle' => "modal", //定义为模拟框 触发按钮
                    'data-id' => 'jt',
                ]);
                ?>
            </div>
        </div>
        <?php
        \yii\bootstrap\Modal::begin([
            'class' => 'modal',
            'id' => 'jt-modal',
            'header' => '<h5>家庭医生签约进校园”学龄儿童服务包</h5>',
        ]);
        ?>
        <div class="title">学龄儿童服务包</div>
        <div class="form-group ">
            <div class="info-title">基础项目</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">传染病疫情防控指导</div>
            <div class="form-table-td2">通过对学校、教职工培训指导，开展传染病疫情防控指导、技术支持等服务</div>
            <div class="form-table-td3">免费</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">儿童常见健康问题指导</div>
            <div class="form-table-td2">针对儿童心理、肥胖、口腔和近视等常见健康问题，通过微信小程序给予相关健康知识推送指导</div>
            <div class="form-table-td3">免费</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">龋齿预防</div>
            <div class="form-table-td2">一到三年级儿童（7-9岁）每年一次窝沟封闭，同时进行龋齿检查、防龋指导</div>
            <div class="form-table-td3">免费</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">预防接种</div>
            <div class="form-table-td2">疫苗接种提醒、咨询、流感疫苗接种指导以及计划内免疫接种</div>
            <div class="form-table-td3">免费</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">中医外治法防治青少年近视（自愿选择，非强制）</div>
            <div class="form-table-td2">中医按摩、点穴、拔罐、耳穴压豆、梅花针等方法防治青少年近视（收费，按照医保收费标准执行。自愿选择。）</div>
            <div class="form-table-td3"><?=Html::checkbox('HealthRecords[field38]',$model->field38)?></div>
        </div>

        <?php
        \yii\bootstrap\Modal::end();
        ?>
        <div class="form-group-info" style="margin: 20px; display: flex;justify-content: space-between;">
            <label for="healthrecords-field29">基本服务包</label>
            <div>
                <?=Html::checkbox('HealthRecords[field43]',true,['disabled'=>'true'])?>
                <?php
                echo Html::a('详情', '#', [
                    'data-target' => '#jc-modal' ,//关联模拟框(模拟框的ID)
                    'data-toggle' => "modal", //定义为模拟框 触发按钮
                    'data-id' => 'jc',
                ]);
                ?>
            </div>
        </div>
        <?php
        \yii\bootstrap\Modal::begin([
            'class' => 'modal',
            'id' => 'jc-modal',
            'header' => '<h5>基本服务包</h5>',
        ]);
        ?>
        <div class="form-group ">
            <div class="info-title" style="text-align: center">基本医疗服务项目</div>
        </div>
        <div class="form-table">
            <div class="form-table-td1">全科预约诊疗</div>
            <div class="form-table-td2">全科门诊就诊时，为签约居民提供专人管理、专人诊疗、预约优先就诊</div>
            <div class="form-table-td3">免费</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">预约转诊</div>
            <div class="form-table-td2">根据病情需要，为签约患者提供上级大医院预约挂号及转诊等服务</div>
            <div class="form-table-td3">免费</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">用药指导</div>
            <div class="form-table-td2">为签约居民提供就医、用药指导服务</div>
            <div class="form-table-td3">免费</div>
        </div>
        <div class="form-group ">
            <div class="info-title">基本公共卫生及健康管理服务项目</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">康复管理</div>
            <div class="form-table-td2">为符合条件的签约居民提供康复服务</div>
            <div class="form-table-td3">免费</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">签约“四个一”服务</div>
            <div class="form-table-td2">建立1份健康档案，签订1份家医签约服务协议，发放1个联系卡/表/手册，定期推送1条短信/微信</div>
            <div class="form-table-td3">免费</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">健康咨询服务</div>
            <div class="form-table-td2">为签约居民提供电话健康咨询服务</div>
            <div class="form-table-td3">免费</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">免费健康信息送达</div>
            <div class="form-table-td2">利用微信、短信、APP等信息化手段，定期发送随访提醒服务或个性化健康教育等信息</div>
            <div class="form-table-td3">免费</div>
        </div>
        <div class="form-group ">
            <div class="info-title">特色服务项目</div>
        </div>
        <div class="form-table ">
            <div class="form-table-td1">朝阳区家医服务APP</div>
            <div class="form-table-td2">登录朝阳区家医服务APP，享受查询、互动，获得科学、权威健康资讯</div>
            <div class="form-table-td3">免费</div>
        </div>


        <?php
        \yii\bootstrap\Modal::end();
        ?>
    </div>
    <div class="title">个人信息</div>
    <?= $form->field($model, 'field29')->textInput(['maxlength' => true, 'placeholder' => '请填写学生姓名']) ?>

    <?= $form->field($model, 'field30')->dropDownList(\common\models\HealthRecordsSchool::find()->select(["group_concat(`name`,`nianji`)"])->indexBy('id')->where(['doctorid' => $doctorid])->column(), ['prompt' => '请选择']) ?>
    <?= $form->field($model, 'field34')->textInput(['maxlength' => true, 'placeholder' => '请填写学生年级']) ?>
    <?= $form->field($model, 'field40')->textInput(['maxlength' => true, 'placeholder' => '请填写学生班级']) ?>
    <?= $form->field($model, 'field41')->textInput(['placeholder' => '请填写' . $model->getAttributeLabel('field41')]) ?>

    <?= $form->field($model, 'field42')->dropDownList(HealthRecords::$field41Txt, ['prompt' => '请选择']) ?>
    <?= $form->field($model, 'field3')->textInput(['maxlength' => true, 'placeholder' => '请填写家长姓名']) ?>

    <?= $form->field($model, 'field4')->textInput(['placeholder' => '请填写' . $model->getAttributeLabel('field4')]) ?>
    <?= $form->field($model, 'field6')->textInput(['placeholder' => '请填写' . $model->getAttributeLabel('field6')]) ?>

    <div style="margin-left: 20px; margin-top: 10px;">同意签约点击提交</div>
    <div class="form-group button">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
            'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>
</div>
