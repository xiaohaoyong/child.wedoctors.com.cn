<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> </title>
</head>
<style>
    body {
        font-family: "Songti SC";
        font-size: 24px;
        text-align: left;
        width: 1024px;
        margin: auto;
    }

    h1 {
        text-align: center;
        line-height: 65px;
    }
</style>
<body>
<div style="padding: 50px;">
    <h1>北京市朝阳区八里庄社区卫生服务中心</h1>
    <h1 style="padding-bottom: 50px;">家庭医生签约服务协议书</h1>
    <div class="content1" style="line-height: 50px;">
        <div style="font-weight: bolder;">尊敬的居民朋友：</div>
        <div class="content2" style="text-indent: 2em;">
            您好！感谢您选择八里庄社区卫生服务中心家医团队签约，为了更好地提供连续性健康管理服务，请您仔细填写以下基本信息，您的个人信息我们将妥善保管：
        </div>
    </div>
    <div style="line-height: 70px;text-indent: 2em;">
        <div>
            <span style="padding-right: 100px;">儿童姓名：<?=$model->field29?></span>
        </div>
        <div>
            <span style="padding-right: 100px;">家长手机：<?=$model->field4?></span>
            <span style="padding-right: 100px;">住址：<?=$model->field6?></span>
        </div>
        <div>
            <span style="padding-right: 100px;">学校及班级：<?=$model->field30?> <?=$model->field34?></span>
            <span style="padding-right: 100px;">校医：<?=$model->field31?></span>
            <span style="padding-right: 100px;">校医电话：<?=$model->field32?></span>
        </div>
        <div>签订协议本着平等、尊重和自愿的原则，您可以根据孩子需要选择一个或多个签约服务包，团队成员将按照协议提供相应服务，服务内容详见附表。您所选择的签约服务包包括：</div>
        <div style="font-weight: bolder;">一、基本服务包（必选）</div>
        <div style="font-weight: bolder;">二、定制服务包（请在您选择的相应服务包前划√）</div>
        <div>√ 学龄儿童服务包</div>
        <div>希望您遵守协议，保持诚信，将孩子的身体健康状况及变化情况如实、及时告知团队成员，并积极配合团队成员工作，遵从医嘱，做好健康自我管理。有任何健康服务需求，都可以联系您的家庭医生团队成员。</div>
        <div>您已为孩子签约，即代表您已授权签约团队成员可调阅您孩子的电子健康档案。团队成员有义务对您的电子健康档案、诊疗记录信息予以保密，未经您的允许，不得提供给第三方。</div>
        <div>
            本协议期限为一年，一年后自动解约。如需续约请及时与您孩子的家庭医生团队成员联系，办理续约手续。如涉及已经收费的服务项目在签约期内未能完成，经双方协商可延期3个月提供服务或退费。在签约期内，您如果因居住地变更等客观原因，可终止现有的签约服务关系，并可根据实际情况重新启动签约程序。
        </div>
        <div>本协议一式三份，三方各执一份，自 <?=date('Y年m月d日',$model->createttime)?>至 <?=date('Y年m月d日',strtotime('+1 year',$model->createttime))?> 止。</div>

    </div>
    <?php
    $healthRecordsSchool=\common\models\HealthRecordsSchool::findOne($model->field30);
    ?>
    <div style="text-indent: 2em; padding-top:40px; line-height: 60px;display: flex;">
        <div style=" width: 450px;">
            <div>签约医生：</div>
            <div>团队服务电话：</div>
            <div>医生签字：<image src="<?php
                if($healthRecordsSchool->sign2){
                    echo $healthRecordsSchool->sign2;
                }
                ?>" style="width: 200px;  "></image></div>
            <div>日期：<?=date('Y-m-d',$model->createtime)?></div>
        </div>
        <div style="width: 450px;">
            <div>儿童姓名：<?=$model->field29?></div>
            <div>身份证号：<?=$model->field15?></div>
            <div>居民签字：<image src="<?php
                if($model->field33){
                    echo $model->field33;
                }
                ?>" style="width: 200px;  "></image></div>
            <div>日期：<?=date('Y-m-d',$model->createtime)?></div>
        </div>
    </div>
    <div style="text-indent: 2em; padding-top:40px; line-height: 60px;margin-bottom: 30px;">
        <div style="width: 450px;">
            <div>校医：</div>
            <div>校医签字：<image src="<?php
                if($healthRecordsSchool->sign1){
                    echo $healthRecordsSchool->sign1;
                }
                ?>"</div>
            <div>日期：<?=date('Y-m-d',$model->createtime)?></div>
        </div>
    </div>
</div>
</div>
</body>
</html>