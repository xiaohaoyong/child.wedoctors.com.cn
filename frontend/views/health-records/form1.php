<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\HealthRecords;

/* @var $this yii\web\View */
/* @var $model common\models\HealthRecords */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    body {
        background: #ffffff;

    }

    .health-records .control-label {
        width: 240px;
    }

    .health-records .w120 {
        width: 120px;
    }
</style>
<div class="health-records health-records-form1">
    <div class="header">
        <div class="info">
            社区定制服务包申请表
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
    echo $form->field($model, 'doctorid')->hiddenInput();
    ?>
    <div class="title">基本信息</div>
    <div class="form-group ">
        <div class="form-group-info"><label class="w120 control-label">申请社区</label>
            <div class="form-input">八里庄社区卫生服务中心</div>
        </div>
    </div>
    <div class="form-group ">
        <div class="form-group-info"><label class="w120 control-label">联系人</label>
            <div class="form-input">刘涛</div>
        </div>
    </div>
    <div class="form-group ">
        <div class="form-group-info"><label class="w120 control-label ">联系电话</label>
            <div class="form-input">13552061388</div>
        </div>
    </div>
    <div class="title">服务包信息</div>
    <div class="form-group ">
        <div class="form-group-info"><label class="w120 control-label">申请理由</label>
            <div class="form-input">提高学龄儿童健康管理水平</div>
        </div>
    </div>
    <div class="form-group ">
        <div class="form-group-info"><label class="w120 control-label">服务包名称</label>
            <div class="form-input">学龄儿童服务包</div>
        </div>
    </div>
    <div class="form-group ">
        <div class="form-group-info"><label class="w120 control-label">针对人群</label>
            <div class="form-input">非重点人群</div>
        </div>
    </div>
    <div class="title">基本服务包</div>
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
        <div class="form-table-td2">疫苗接种提醒、咨询、指导以及计划内免疫接种</div>
        <div class="form-table-td3">免费</div>
    </div>
    <div class="form-table ">
        <div class="form-table-td1">中医外治法防治青少年近视（自愿选择，非强制）</div>
        <div class="form-table-td2">中医按摩、点穴、拔罐、耳穴压豆、梅花针等方法防治青少年近视</div>
        <div class="form-table-td3"><?=Html::checkbox('HealthRecords[field38]',false)?></div>
    </div>
    <div class="form-group" style="line-height: 30px;text-align: center">
        <a href="/img/xieyi.pdf">查看协议模板</a>
    </div>

    <div class="form-group button">
        <?= Html::submitButton($model->isNewRecord ? '确认并签约' : '确认并签约', ['class' => $model->isNewRecord ? 'btn btn-success' :
            'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
