<?php
use yii\widgets\ActiveForm;
?>
<style>

    h1 {
        text-align: center;
        line-height: 30px;
        font-size: 16px;
    }
</style>
<?php
$healthRecordsSchool = \common\models\HealthRecordsSchool::findOne($model->field30);
$doctor=\common\models\UserDoctor::findOne(['userid'=>$healthRecordsSchool->doctorid]);
$hospital=\common\models\Hospital::findOne($doctor->hospitalid);
?>
<div style="padding: 10px; max-width: 900px; margin: 0 auto;">
    <h1>北京市<?=\common\models\Area::$all[$doctor->county]?><?=$hospital->name?></h1>
    <h1 style="padding-bottom: 20px;">家庭医生签约服务协议书</h1>
    <div class="content1" style="line-height: 25px;">
        <div style="font-weight: bolder;">尊敬的<?= $model->field29 ?>家长：</div>
        <div style="text-indent: 2em;">
            <p>
                您好！感谢您为孩子选择 <?=$healthRecordsSchool->doctor_name?>签约，本着平等、尊重和自愿的原则，三方签订本协议书。为了更好提供传染病和儿童常见病防控、儿童健康管理等学校卫生服务，根据孩子健康需求选择家庭医生签约基本服务包<?=$model->field44?'、学龄儿童服务包':''?>，团队成员将按照协议内容提供相应服务，内容详见附表。
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

    <div style="font-size: 12px; padding-top:20px; line-height: 30px;">
        <div style="width: 50%">
            <div>家庭医生：<?= $healthRecordsSchool->family_name ?></div>
            <div>团队服务电话：<?= $healthRecordsSchool->doctor_phone ?></div>
            <div>日期：<?= date('Y-m-d', $model->createtime) ?></div>

            <div>


                <div style="float: left; height: 100px; line-height: 100px;">
                    医生签字：
                </div>
                <div style="float: left; height: 100px;">
                    <image id="rotate" src="<?php
                    if ($healthRecordsSchool->sign2) {
                        echo $healthRecordsSchool->sign2;
                    }
                    ?>" style="width: 50px; "></image>
                </div>
                <div style=" clear:both;"></div>
            </div>
        </div>
        <div style="width: 50%;">
            <div>学生姓名：<?= $model->field29 ?></div>
            <div>身份证号：<?= $model->field41 ?></div>
            <div>日期：<?= date('Y-m-d', $model->createtime) ?></div>

            <div>
                <div style="float: left; height: 100px; line-height: 100px;">
                    家长签字：</div>
                <div style="float: left; height: 100px;">
                    <image id="jimg" src="<?php
                    if ($model->field33) {
                        echo $model->field33;
                    }
                    ?>" style="width: 50px;  "></image>
                </div>
                <div style=" clear:both;"></div>

            </div>
        </div>
        <div style="margin-top: 20px;">
            <div>学校名称：<?= $healthRecordsSchool->name ?></div>
            <div>校医姓名：<?= $healthRecordsSchool->school_name ?></div>
            <div>日期：<?= date('Y-m-d', $model->createtime) ?></div>

            <div>

                <div style="float: left; height: 100px; line-height: 100px;">
                    校医签字：</div>
                <div style="float: left; height: 100px;">
                    <image id="simg" src="<?php
                    if ($healthRecordsSchool->sign1) {
                        echo $healthRecordsSchool->sign1;
                    }
                    ?>" style="width: 50px;  "></image>
                </div>
                <div style=" clear:both;"></div>

            </div>

        </div>
    </div>
</div>



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
    .form-table{ display: -webkit-box; /* 老版本语法: Safari, iOS, Android browser, older WebKit browsers. */
        display: -moz-box; /* 老版本语法: Firefox (buggy) */
        display: -ms-flexbox; /* 混合版本语法: IE 10 */
        display: -webkit-flex; /* 新版本语法: Chrome 21+ */
        display: flex; /* 新版本语法: Opera 12.1, Firefox 22+ */
        -webkit-flex-wrap: nowrap;
        -ms-flex-wrap: nowrap;
        flex-wrap: nowrap;
        -webkit-box-pack: justify;
        -moz-justify-content: space-between;
        -webkit-justify-content: space-between;
        justify-content: space-between;
    }
</style>
<div class="health-records health-records-form1" style="margin: 0 auto;margin-bottom: 50px;margin-top:200px;max-width: 900px;">
    <div class="header">
        <div class="info">
            附件：社区定制服务包
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
        <div class="form-table-td2">根据病情需要，为签约患者提供<?=$healthRecordsSchool->doctorid!=206262?'上级大医院':'医联体天坛医院、南苑医院、丰台区妇幼保健院'?>预约挂号及转诊等服务</div>
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
        <div class="form-table-td1"><?=\common\models\Area::$all[$doctor->county]?>家医服务APP</div>
        <div class="form-table-td2">登录<?=\common\models\Area::$all[$doctor->county]?>家医服务APP，享受查询、互动，获得科学、权威健康资讯</div>
        <div class="form-table-td3">免费</div>
    </div>
    <?php
    if($model->field44){
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
    <?php
    if($healthRecordsSchool->doctorid!=206262) {
        ?>
    <div class="form-table ">
        <div class="form-table-td1">龋齿预防</div>
        <div class="form-table-td2">一到三年级儿童（7-9岁）每年一次窝沟封闭，同时进行龋齿检查、防龋指导</div>
        <div class="form-table-td3">免费</div>
    </div>
        <?php }?>
    <div class="form-table ">
        <div class="form-table-td1">预防接种</div>
        <div class="form-table-td2">疫苗接种提醒、咨询、流感疫苗接种指导以及计划内免疫接种</div>
        <div class="form-table-td3">免费</div>
    </div>
        <?php
        if($healthRecordsSchool->doctorid==206262) {
            ?>
            <div class="form-table ">
                <div class="form-table-td1">口腔筛查</div>
                <div class="form-table-td2">免费（需持卡挂号）口腔科检查，指导儿童正确刷牙方法，每年提供一次氟化泡沫预防龋齿</div>
                <div class="form-table-td3">免费</div>
            </div>
            <div class="form-table ">
                <div class="form-table-td1">龋齿预防</div>
                <div class="form-table-td2">一到三年级儿童（7-9岁）每年一次窝沟封闭，同时进行龋齿检查、防龋指导（收费，按照医保收费标准执行。自愿选择。）</div>
                <div class="form-table-td3"><?=\yii\bootstrap\Html::checkbox('HealthRecords[field36]',$model->field38,['disabled'=>'true'])?></div>
            </div>
        <?php }?>
    <div class="form-table ">
        <div class="form-table-td1">中医外治法防治青少年近视（自愿选择，非强制）</div>
        <div class="form-table-td2">中医按摩、点穴、拔罐、耳穴压豆、梅花针等方法防治青少年近视（收费，按照医保收费标准执行。自愿选择。）</div>
        <div class="form-table-td3"><?=\yii\bootstrap\Html::checkbox('HealthRecords[field38]',$model->field38,['disabled'=>'true'])?></div>
    </div>
    <?php }?>
    <?php ActiveForm::end(); ?>
</div>

<script>
    window.onload = function () {

        var kuan = document.getElementById("rotate").width;
        var gao = document.getElementById("rotate").height;

        if (kuan < gao) {
            document.getElementById('rotate').style.setProperty('-webkit-transform','rotate(270deg)');

        }

        var kuan = document.getElementById("jimg").width;
        var gao = document.getElementById("jimg").height;

        if (kuan < gao) {
            document.getElementById('jimg').style.setProperty('-webkit-transform','rotate(270deg)');

        }
        var kuan = document.getElementById("simg").width;
        var gao = document.getElementById("simg").height;

        if (kuan < gao) {
            document.getElementById('simg').style.setProperty('-webkit-transform','rotate(270deg)');

        }

    };
</script>
