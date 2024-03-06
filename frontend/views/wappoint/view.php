<?php
$this->title='我的预约';
\common\assets\JqAlert::register($this);

?>
<div class="body">
    <div class="logo">
        <div class="img">
            <image src="/img/appoint_view_loge.png" style="width: 66px;height: 58px;"></image>
        </div>
        <div class="title">
            <div class="title1"><?=\common\models\Appoint::$stateText[$row['state']]?></div>
            <?php if($row['state']==1){?><div class="title2"> 凭此二维码到社区接种</div><?php }?>
        </div>
    </div>

    <div class="container">
        <?php if($row['state']==1){?>
        <div class="content1">
            <div class="qrcode">
                <image src="https://api.child.wedoctors.com.cn/image/qr-code?id=<?=$row['id']?>" style="width: 225px;height: 225px;"></image>
            </div>
            <div id="myTime">

            </div>
        </div>
        <?php }elseif($row['state']==6){?>
        <div class="content1">
            <div style="padding: 30px;text-align: center;color: red; line-height: 20px;">
            您的预约已提交，目前排队处理中，系统会及时将预约结果告知您，感谢您的支持
            </div>
        </div>
        <?php }elseif($row['state']==0){?>
            您的预约已提交，社区审核中...
        <?php }?>
        <div class="zhong">
            <div class="left"></div>
            <div class="middle"></div>
            <div class="right"></div>
        </div>
        <div class="content2">
            <div class="item">
                <div>预约人</div>
                <div><?=$row['child_name']?></div>
            </div>
            <div class="item">
                <div>预约项目</div>
                <div><?=$row['type']?></div>
            </div>
            <div class="item">
                <div>预约社区</div>
                <div><?=$row['hospital']?></div>
            </div>
            <div class="item">
                <div>预约时间</div>
                <div><?=$row['time']?></div>
            </div>
            <?php if($row['vaccineStr']){?>
                <div class="item">
                    <div>预约疫苗</div>
                    <div><?=$row['vaccineStr']?></div>
                </div>
            <?php }?>
            <?php if($row['sStr']){?>
                <div class="item">
                    <div>街道/社区</div>
                    <div><?=$row['sStr']?></div>
                </div>
            <?php }?>
            <?php if($row['state']==1){?>
            <div class="item">
                <div>排队序号</div>
                <div><?=$row['duan']?>-<?=$row['index']?>号</div>
            </div>
            <?php }?>

        </div>
    </div>
    <div class="myAppoint"><?=\yii\helpers\Html::a('我的预约',['wappoint/my'])?></div>
</div>
<div class="appoint_my"><a href="/wappoint/my"><img src="/img/appoint_my.png" width="56" height="56"></a></div>
<?php
$updateJs = <<<JS
    var doctorid={$row['doctorid']};
    if(doctorid == 38){
               jQuery.confirm({
                    title: '温馨提醒',
                    content: "请您务必注意保健科地址：建功北里三区3号楼1层北侧白纸坊社区卫生服务中心预防保健区（一定注意：保健科在菜园街街边上，不在中心院内）成人常态化门诊时间：工作日周二13:30-15:00",
                    type: 'green',
                    buttons: {
                        ok: {
                            text: "确认知晓",
                            btnClass: 'btn-success',
                            keys: ['enter']
                        }
                    }
                });
            }
				//定时器 
				var clock1 = window.setInterval(function showTime() {
                    var time = new Date(); /*获取当前时间 年月日时分秒*/
                    var y = time.getFullYear();
                    var mon = time.getMonth() + 1; //0-11 
                    var d = time.getDate();
                    var h = time.getHours();
                    var m = time.getMinutes();
                    var s = time.getSeconds();
                    /*向div中插入内容   年月日时分秒  val()只能用在表单中*/
                    $("#myTime").html(y + "年" + mon + "月" + d + "日 " + h + ":" + m + ":" + s);
                }, 1000);
JS;
$this->registerJs($updateJs);

?>