<?php
$app = \EasyWeChat\Factory::officialAccount(\Yii::$app->params['easywechat']);
$cache = \Yii::$app->rdmp;

$app->rebind('cache', $cache);
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>

<script>
    wx.config(<?=$app->jssdk->buildConfig(['wx-open-launch-weapp', true]);?>);

</script>
<div class="wrapper appoint_list">
    <style>.btn { width: 200px; height: 200px }</style>

    <wx-open-launch-weapp
            id="launch-btn"
            appid="wx9977e00637216db7"
            path="pages/lanhu_menzhenzhuye/component.html?id=1556"
    >
        <script type="text/wxtag-template">
            <button class="btn">test2</button>
        </script>
    </wx-open-launch-weapp>
    <style>
        #launch-btn {
            width: 100px;
            height: 100px
        }
    </style>
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
                <?php if($v['userid'] == 1301729){?>

            <wx-open-launch-weapp
                    id="launch-btn1"
                    appid="wx9977e00637216db7"
                    path="pages/lanhu_menzhenzhuye/component.html?id=1556"
            >
                <div class="item">
                    <div class="item-content">
                        <div class="hospital_log"><img src="/img/appoint_type_loge.png" width="46" height="35"/>
                        </div>
                        <div class="hospital">
                            <div class="name">微医全科诊所（扩龄可约）</div>
                                <div class="address">接种时间：周二、周三、周五、周六</div>
                        </div>
                    </div>
                    <div class="item-button">
                        <div class="phone"><img src="/img/appoint_list_phone.png"
                                                                                 width="18" height="18"/></div>
                        <script type="text/wxtag-template">

                            <button class="button">
                                在线预约1</button>
                        </script>


                    </div>
                    <div class="rad">注：请仔细阅读温馨提示，并确认是否可在本社区接种后预约</div>
                </div>
            </wx-open-launch-weapp>

                    <div class="item">
                        <div class="item-content">
                            <div class="hospital_log"><img src="/img/appoint_type_loge.png" width="46" height="35"/>
                            </div>
                            <div class="hospital">
                                <div class="name">王府中西医结合医院</div>
                                <div class="address">接种时间：周一至周六8:00-12:00</div>
                            </div>
                        </div>
                        <div class="item-button">
                            <div class="phone"><img src="/img/appoint_list_phone.png"
                                                    width="18" height="18"/></div>
                            <a class="button" href="" onclick="return false;">
                                在线预约</a>

                        </div>
                        <div class="rad">注：请仔细阅读温馨提示，并确认是否可在本社区接种后预约</div>
                    </div> <div class="item">
                        <div class="item-content">
                            <div class="hospital_log"><img src="/img/appoint_type_loge.png" width="46" height="35"/>
                            </div>
                            <div class="hospital">
                                <div class="name">北京家恩德仁医院</div>
                                <div class="address">接种时间：周一至周日9:00-18:00</div>
                            </div>
                        </div>
                        <div class="item-button">
                            <div class="phone"><img src="/img/appoint_list_phone.png"
                                                    width="18" height="18"/></div>
                            <a class="button" href="" onclick="return false;">
                                在线预约</a>

                        </div>
                        <div class="rad">注：请仔细阅读温馨提示，并确认是否可在本社区接种后预约</div>
                    </div> <div class="item">
                        <div class="item-content">
                            <div class="hospital_log"><img src="/img/appoint_type_loge.png" width="46" height="35"/>
                            </div>
                            <div class="hospital">
                                <div class="name">北京和美妇儿医院</div>
                                <div class="address">接种时间：周一至周日8:00-17:00</div>
                            </div>
                        </div>
                        <div class="item-button">
                            <div class="phone"><img src="/img/appoint_list_phone.png"
                                                    width="18" height="18"/></div>
                            <a class="button" href="" onclick="return false;">
                                在线预约</a>

                        </div>
                        <div class="rad">注：请仔细阅读温馨提示，并确认是否可在本社区接种后预约</div>
                    </div>
                <?php }?>
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