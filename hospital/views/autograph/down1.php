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
    <h1 style="padding-bottom: 50px;font-size: 34px;">朝阳区社区卫生服务机构家庭医生服务协议书 </h1>
    <div class="content1" style="line-height: 50px;">
        <div style="font-weight: bolder;">尊敬的居民朋友:</div>
        <div class="content2" style="text-indent: 2em;">
            您好！感谢您选择<?=\common\models\Area::$all[Yii::$app->user->identity->county]?><?=$userDoctor['name']?>签约，本着平等、尊重和自愿的原则，双方签订本协议书。您根据自身需要选择 <span style="text-decoration:underline">基本服务包、0-6岁儿童签约服务包</span> ，团队成员将按照协议内容提供相应服务，内容详见附表。
        </div>
    </div>
    <div style="line-height: 70px;text-indent: 2em;">
        <div>希望您遵守协议，保持诚信，将自己的身体健康状况及变化情况如实、及时告知团队成员，并积极配合团队成员工作，遵从医嘱，做好健康自我管理。有任何健康服务需求，都可以联系您的家庭医生团队成员。</div>
        <div>您已签约，即代表您已授权签约团队成员可调阅您的电子健康档案和您在其他医疗机构的诊疗记录信息。团队成员有义务对您的电子健康档案、诊疗记录信息予以保密，未经您的允许，不得提供给第三方。 </div>

        <div>本协议期限为一年，一年后自动续约。如需解约请及时与您的家庭医生团队成员联系，办理解约手续。如涉及已经收费的服务项目在签约期内未能完成，经双方协商可延期3个月提供服务或退费。在签约期内，您如果因居住地变更等客观原因，可终止现有的签约服务关系，并可根据实际情况重新启动签约程序。 </div>

        <?php $autograph=\common\models\Autograph::findOne(['userid'=>$userid]); ?>
        <div>本协议一式二份，双方各执一份，自 <?=date('Y年m月d日',strtotime($autograph->starttime))?> 至 <?=date('Y年m月d日',strtotime($autograph->endtime))?> 止。</div>
        <div>首次签约日期： <?=date('Y年m月d日',$autograph->createtime)?></div>

    </div>
    <div style="text-indent: 2em; padding-top:40px; height: 200px; line-height: 60px;">
        <div style="float: left; width: 450px;">
            <div>签约医生：<?=$userDoctor['name']?></div>
            <div>团队服务电话：<?=$userDoctor['phone']?></div>
            <div>医生签字：<?=$userDoctor['name']?></div>
            <div>日期：<?=date('Y年m月d日',strtotime($autograph->starttime))?></div>
        </div>
        <div style="float: left;">
            <div>签约儿童姓名：<?=$child?implode(',',$child):''?></div>
            <div>儿童身份证号：<?=$childid?implode(',',$childid):''?></div>
            <div style="    position:relative;">家长签字：<image src="<?php
                if($autograph){
                    echo $autograph->img;
                }
                ?>" style="width: 200px; position: absolute; top: -30px; "></image></div>
            <div>日期：<?=date('Y年m月d日',strtotime($autograph->starttime))?></div>
        </div>
    </div>
</div>
</div>
</body>
</html>