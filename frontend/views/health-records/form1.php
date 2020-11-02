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
<div class="health-records">
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
    <div class="title">包含服务项</div>

    <div class="form-group ">
        <div class="form-group-info"><label class="control-label">传染病疫情防控指导</label>
            <div class="form-input"><?=Html::checkbox('HealthRecords[field39]',true,['onclick' => "return false;"])?></div>
        </div>
    </div>
    <div class="form-group ">
        <div class="form-group-info"><label class="control-label">儿童常见健康问题指导</label>
            <div class="form-input"><?=Html::checkbox('HealthRecords[field35]',true,['onclick' => "return false;"])?></div>
        </div>
    </div>
    <div class="form-group ">
        <div class="form-group-info"><label class="control-label">龋齿预防</label>
            <div class="form-input"><?=Html::checkbox('HealthRecords[field36]',true,['onclick' => "return false;"])?></div>
        </div>
    </div>
    <div class="form-group ">
        <div class="form-group-info"><label class="control-label">预防接种</label>
            <div class="form-input"><?=Html::checkbox('HealthRecords[field37]',true,['onclick' => "return false;"])?></div>
        </div>
    </div>
    <div class="form-group ">
        <div class="form-group-info"><label class="control-label">中医外治法防治青少年近视（自愿选择，非强制）</label>
            <div class="form-input"><?=Html::checkbox('HealthRecords[field38]',false)?></div>
        </div>
    </div>
    <div class="title" style="height: auto">中医外治法防治青少年近视（自愿选择，非强制）服务描述：中医按摩、点穴、拔罐、耳穴压豆、梅花针等方法防治青少年近视，收费标准：202元</div>

    <div class="form-group button">
        <?= Html::submitButton($model->isNewRecord ? '确认并签约' : '确认并签约', ['class' => $model->isNewRecord ? 'btn btn-success' :
            'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
