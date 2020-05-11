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
    <h1>北京市<?=\common\models\Area::$all[Yii::$app->user->identity->county]?>社区卫生服务机构</h1>
    <h1 style="padding-bottom: 50px;">家庭医生签约服务协议书</h1>
    <div class="content1" style="line-height: 50px;">
        <div style="font-weight: bolder;">尊敬的居民朋友：</div>
        <div class="content2" style="text-indent: 2em;">
            您好！感谢您选择<?=\common\models\Area::$all[Yii::$app->user->identity->county]?>社区卫生服务中心家医团队签约，为了更好地提供连续性健康管理服务，请您仔细填写以下基本信息，您的个人信息我们将妥善保管：
        </div>
    </div>
    <div style="line-height: 70px;text-indent: 2em;">
        <div>
            <span style="padding-right: 100px;">姓名：<?=$userParent['mother']?></span>
            <span style="padding-right: 100px;">性别：<?=$userParent['mother']?'女':''?></span>
            <span style="padding-right: 100px;">年龄：
                <?php
                if($userParent['field28']) {
                    list($year, $month, $day) = explode("-", $userParent['field28']);
                    $year_diff = date("Y") - $year;
                    $month_diff = date("m") - $month;
                    $day_diff = date("d") - $day;
                    if ($day_diff < 0 || $month_diff < 0)
                        $year_diff--;
                    echo $year_diff;
                }
                ?>
            </span>
            <span style="padding-right: 100px;">住宅电话：</span>
        </div>
        <div>宝宝姓名：<?=$child?implode(',',$child):''?></div>
        <div>手机：<?=$userParent['mother_phone']?$userParent['mother_phone']:''?></div>
        <div>住址：<?=$userParent['address']?></div>
        <div>紧急联系人：<?=$userParent['father']?></div>
        <div>关系：<?=$userParent['mother']?'夫妻':''?></div>
        <div>紧急联系人电话：<?=$userParent['father_phone']?$userParent['father_phone']:''?></div>
        <div>签订协议本着平等、尊重和自愿的原则，您可以根据自身需要选择一个或多个签约服务包，团队成员将按照协议提供相应服务，服务内容详见附表。您所选择的签约服务包包括：</div>
        <div style="font-weight: bolder;">一、基本服务包（必选）</div>
        <div style="font-weight: bolder;">二、个性服务包（请在您选择的相应服务包前划√）</div>
        <div>□高血压患者签约服务包 □2型糖尿病患者签约服务包 <?=$_GET['t']?'√':'□'?>育龄妇女签约服务包 <?=$_GET['t']?'□':'√'?>0-6岁儿童签约服务包 □重点人群身心健康签约服务包 □65岁及以上老年人签约服务包</div>
        <div style="font-weight: bolder;">三、定制服务包（请在您选择的相应服务包前划√）</div>
        <div> □医养结合服务包 □计生关爱服务包 □其他</div>
        <div>希望您遵守协议，保持诚信，将自己的身体健康状况及变化情况如实、及时告知团队成员，并积极配合团队成员工作，遵从医嘱，做好健康自我管理。有任何健康服务需求，都可以联系您的家庭医生团队成员。</div>
        <div>您已签约，即代表您已授权签约团队成员可调阅您的电子健康档案和您在其他医疗机构的诊疗记录信息。团队成员有义务对您的电子健康档案、诊疗记录信息予以保密，未经您的允许，不得提供给第三方。</div>
        <div>
            本协议期限为一年，一年后自动续约。如需解约请及时与您的家庭医生团队成员联系，办理解约手续。如涉及已经收费的服务项目在签约期内未能完成，经双方协商可延期3个月提供服务或退费。在签约期内，您如果因居住地变更等客观原因，可终止现有的签约服务关系，并可根据实际情况重新启动签约程序。
        </div>
        <?php $autograph=\common\models\Autograph::findOne(['userid'=>$userid]); ?>
        <div>本协议一式二份，双方各执一份</div>
    </div>
    <div style="text-indent: 2em; padding-top:40px; height: 200px; line-height: 60px;">
        <div style="float: left; width: 450px;">
            <div>签约医生：<?=$userDoctor['name']?></div>
            <div>团队服务电话：<?=$userDoctor['phone']?></div>
            <div>医生签字：<?=$userDoctor['name']?></div>

        </div>
        <div style="float: left;">
            <div>居民姓名：<?=$userParent['mother']?></div>
            <div>身份证号：<?=$userParent['mother_id']?></div>
            <div style="    position:relative;">居民签字：<image src="<?php
                if($autograph){
                    echo $autograph->img;
                }
                ?>" style="width: 200px; position: absolute; top: -30px; "></image></div>
        </div>
    </div>
</div>
</div>
</body>
</html>