<div class="wrapper">
    <div class="content-wrapper">
        <div class="search">

            <form method="get" action="">
                <input type="search" name="search" placeholder="搜索社区">
            </form>
        </div>
        <div class="box">
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
        </div>
        <div class="list">
            <?php
            foreach ($doctors as $k => $v) {
                ?>
                <div class="item" data-toggle="modal" data-target="#create-modal<?= $v['userid'] ?>">
                    <div class="qrcode"><img
                                src="/img/view_hospital_logo.png"/>
                    </div>
                    <div class="hospital">
                        <div class="name"><?= $v['name'] ?></div>
                        <div class="address">社区电话：<?= $v['phone'] ?></div>
                        <div class="enlarge">点击放大识别二维码</div>
                    </div>
                </div>


                <?php
                \yii\bootstrap\Modal::begin([
                    'id' => 'create-modal' . $v['userid'],
                    'header'=>$v['name']
                ]);
                ?>
                <?=\yii\bootstrap\Html::a('去预约',['wappoint/view','userid'=>$v['userid']])?>
                <div style="text-align: center">长按识别二维码</div>
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