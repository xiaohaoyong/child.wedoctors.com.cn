<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
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
        <div style="font-weight: bolder;">尊敬的<?= $model->field29 ?>家长：</div>
        <div class="content2" style="text-indent: 2em;">
            <p>
                您好！感谢您为孩子选择朝阳区 八里庄社区卫生服务中心
                团队签约，本着平等、尊重和自愿的原则，三方签订本协议书。为了更好提供传染病和儿童常见病防控、儿童健康管理等学校卫生服务，根据孩子健康需求选择基本服务包、学龄儿童服务包，团队成员将按照协议内容提供相应服务，内容详见附表。
            </p>
            <p>
                希望您遵守协议，保持诚信，将孩子的身体健康状况及变化情况如实、及时告知我们，并积极配合团队成员工作，遵从医嘱，做好健康自我管理。有任何健康服务需求，都可以联系您的家庭医生团队成员。
            </p>
            <p>
                您已签约，即代表您已授权签约团队成员可调阅您孩子的电子健康档案信息。团队成员有义务对您的电子健康档案诊疗记录信息予以保密，未经您的允许，不得提供给第四方。
            </p>
            <p>
                本协议期限为一年，一年后自动解约。如需续约请及时与学校、医生联系，办理续约手续。如涉及已经收费的服务项目在签约期内未能完成，经三方协商可延期3个月提供服务或退费。在签约期内，您如果因居住地变更等客观原因，可终止现有的签约服务关系，并可根据实际情况重新启动签约程序。
            </p>
            <div>本协议一式三份，三方各执一份，自 <?= date('Y年m月d日', $model->createtime) ?>
                至 <?= date('Y年m月d日', strtotime('+1 year', $model->createtime)) ?> 止。
            </div>

        </div>
    </div>
    <?php
    $healthRecordsSchool = \common\models\HealthRecordsSchool::findOne($model->field30);
    ?>
    <div style="text-indent: 2em; padding-top:40px; line-height: 60px;display: flex;justify-content: space-between;flex-wrap: wrap;">
        <div style="width: 50%">
            <div>家庭医生：<?= $healthRecordsSchool->doctor_name ?></div>
            <div>团队服务电话：<?= $healthRecordsSchool->doctor_phone ?></div>
            <div style="display: flex;align-items: center; height: 50px;">
                <div>
                    医生签字：
                </div>
                <div>
                    <image id="rotate" src="<?php
                    if ($healthRecordsSchool->sign2) {
                        echo $healthRecordsSchool->sign2;
                    }
                    ?>" style="width: 100px;    "></image>
                </div>
            </div>
            <div>日期：<?= date('Y-m-d', $model->createtime) ?></div>
        </div>
        <div style="width: 50%;">
            <div>学生姓名：<?= $model->field29 ?></div>
            <div>身份证号：<?= $model->field41 ?></div>
            <div style="display: flex;align-items: center; height: 50px;">
                <div>家长签字：</div>
                <div>
                    <image id="jimg" src="<?php
                    if ($model->field33) {
                        echo $model->field33;
                    }
                    ?>" style="width: 100px;  "></image>
                </div>
            </div>
            <div>日期：<?= date('Y-m-d', $model->createtime) ?></div>
        </div>
        <div style="margin-top: 20px;">
            <div>学校名称：<?= $healthRecordsSchool->name ?></div>
            <div>校医姓名：<?= $healthRecordsSchool->school_name ?></div>
            <div style="display: flex;align-items: center; height: 50px;">

                <div>校医签字：</div>
                <div>
                    <image id="simg" src="<?php
                    if ($healthRecordsSchool->sign1) {
                        echo $healthRecordsSchool->sign1;
                    }
                    ?>"
                </div>
            </div>
        </div>
        <div>日期：<?= date('Y-m-d', $model->createtime) ?></div>
    </div>
</div>

</div>
</div>
<script>
    window.onload = function () {

        var kuan = document.getElementById("rotate").width;
        var gao = document.getElementById("rotate").height;

        if (kuan < gao) {
            document.getElementById('rotate').style.transform = 'rotate(270deg)';
        }

        var kuan = document.getElementById("jimg").width;
        var gao = document.getElementById("jimg").height;

        if (kuan < gao) {
            document.getElementById('jimg').style.transform = 'rotate(270deg)';
        }
        var kuan = document.getElementById("simg").width;
        var gao = document.getElementById("simg").height;

        if (kuan < gao) {
            document.getElementById('simg').style.transform = 'rotate(270deg)';
        }

    };
</script>
</body>
</html>