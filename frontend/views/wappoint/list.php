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
                        <li><a href="?county=<?= $k ?>"><?= $v ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="search">
                <form method="get" action="">
                    <input type="search" name="search" placeholder="搜索社区">
                </form>
            </div>
        </div>
        <div class="list">
            <?php
            foreach ($doctors as $k => $v) {
                ?>
                <div class="item" data-toggle="modal" data-target="#create-modal<?= $v['userid'] ?>">
                    <div class="item-content">
                        <div class="hospital_log"><img src="/img/appoint_type_loge.png" width="46" height="35"/>
                        </div>
                        <div class="hospital">
                            <div class="name"><?= $v['name'] ?></div>
                            <?php if ($v['week']) { ?>
                                <div class="address">门诊时间：每周工作日 <?= implode('，', $v['week']) ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="item-button">
                        <div class="phone"><a href="tel:<?= $v['phone'] ?>"><img src="/img/appoint_list_phone.png"
                                                                                 width="18" height="18"/></a></div>
                        <?php if ($v['week']) { ?>
                            <div class="button">在线预约</div>
                        <?php }else{ ?>
                            <div class="button on">暂未开通</div>
                        <?php }?>

                    </div>
                </div>


                <?php
                \yii\bootstrap\Modal::begin([
                    'id' => 'create-modal' . $v['userid'],
                    'header' => $v['name']
                ]);
                ?>
                <?=$v['appoint_intro']?>
                <?php if ($v['week']) { ?>
                    <?= \yii\bootstrap\Html::a('去预约', ['wappoint/from', 'userid' => $v['userid']],['class'=>'button']) ?>
                <?php }else{ ?>
                    <div class="button on">暂未开通</div>
                <?php }?>

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
<?php
\yii\bootstrap\Modal::begin([
    'id' => 'create-modal',
]);
\yii\bootstrap\Modal::end();
?>