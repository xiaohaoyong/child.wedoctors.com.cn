<?php

$mpWechat = new \common\vendor\MpWechat([
    'token' => \Yii::$app->params['WeToken'],
    'appId' => \Yii::$app->params['AppID'],
    'appSecret' => \Yii::$app->params['AppSecret'],
    'encodingAesKey' => \Yii::$app->params['encodingAesKey']
]);
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>
<script>
wx.config(<?=json_encode($mpWechat->jsApiConfig(['openTagList'=>['wx-open-launch-weapp']]));?>);
</script>
<div class="wrapper appoint_list">
    <div class="content-wrapper">
        <div class="search_box">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?= $county ? \common\models\Area::$all[$county] : "选择区/县" ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <?php foreach (\common\models\Area::$county['11'] as $k => $v) { ?>
                        <li><a href="?county=<?= $k ?>&type=<?=$type?>"><?= $v ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="search">
                <form method="get" action="">
                    <input type="search" name="search" placeholder="搜索社区">
                    <input type="hidden" name="type" value="<?=$type?>">

                </form>
            </div>
        </div>
        <style>
            .modal_title text {
                font-weight: bold;
                font-size: 16px
            }
            .modal_body{line-height: 24px;}
            .rad{color: rgba(240,85,70,1); font-size: 14px; margin-top: 10px;}
        </style>
        <div class="list">
            <?php
            foreach ($doctors as $k => $v) {
                ?>
                <div class="item">
                    <div class="item-content" data-toggle="modal" data-target="#create-modal<?= $v['userid'] ?>">
                        <div class="hospital_log"><img src="/img/appoint_type_loge.png" width="46" height="35"/>
                        </div>
                        <div class="hospital">
                            <div class="name"><?= $v['name'] ?></div>
                            <?php if ($v['week']) { ?>
                                <div class="address">接种时间：每周工作日 <?= implode('，', $v['week']) ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="item-button">
                        <div class="phone"><a href="tel:<?= $v['phone'] ?>"><img src="/img/appoint_list_phone.png"
                                                                                 width="18" height="18"/></a></div>
                        <?php if ($v['week']) { ?>
                        <a class="button" href="" onclick="return false;" data-toggle="modal" data-target="#create-modal<?= $v['userid'] ?>">
                            在线预约
                        </a>
                        <?php } else { ?>
                            <div class="button on">暂未开通</div>
                        <?php } ?>

                    </div>
                    <div class="rad">注：请仔细阅读温馨提示，并确认是否可在本社区接种后预约</div>
                </div>
                
                <?php
                \yii\bootstrap\Modal::begin([
                    'id' => 'create-modal' . $v['userid'],
                    'class' => 'create-modal',
                    'header' => $v['name']
                ]);
                ?>
                <div class="modal_body">
                    <div class="modal_title">
                        <text>预约周期：</text><?= $v['cycleDay'] ?>天
                    </div>
                    <div class="modal_title">
                        <text>新号放号时间：</text><?= $v['release_time'] ?></div>

                    <div class="modal_title">
                        <text>温馨提醒：</text>
                    </div>
                    <?= str_replace("\n", "<br>", $v['appoint_intro']) ?>
                    <?php if ($v['week']) { ?>
                        <?= \yii\bootstrap\Html::a('去预约', ['wappoint/from', 'userid' => $v['userid']], ['class' => 'button']) ?>
                    <?php } else { ?>
                        <div class="button on">暂未开通</div>
                    <?php } ?>
                </div>
                <?php
                \yii\bootstrap\Modal::end();
                ?>

                <?php
            }
            ?>
        </div>
        <div class="fo">
            查询更多请点击搜索
        </div>
    </div>
</div>
<div class="appoint_my"><a href="/wappoint/my"><img src="/img/appoint_my.png" width="56" height="56"></a></div>

<?php
\yii\bootstrap\Modal::begin([
    'id' => 'create-modal',
]);
\yii\bootstrap\Modal::end();
?>