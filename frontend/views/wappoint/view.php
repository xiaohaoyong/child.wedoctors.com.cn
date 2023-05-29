<?php
$this->title='我的预约';
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
        </div>
        <?php }elseif($row['state']==6){?>
        <div class="content1">
            <div style="padding: 30px;text-align: center;color: red; line-height: 20px;">
            您的预约已提交，目前排队处理中，系统将在2小时内将预约结果告知您，感谢您的支持
            </div>
        </div>
        <?php }elseif($row['state'==0]){?>
            您的预约已提交，社区审核中
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
