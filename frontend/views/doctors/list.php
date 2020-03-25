<?php
frontend\assets\AppAsset::register($this);
$this->title = "社区医院查询"
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= \yii\helpers\Html::csrfMetaTags() ?>
        <title><?= \yii\helpers\Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <style>
            .content-wrapper {
                padding: 0 16px;
            }

            .search {
                height: 45px;
                padding-left: 25px;
                margin: 0 10px;
                border-bottom: 1px solid #DADBDF;
                background: url("/img/search.png") no-repeat center left;
                background-size: 16px 16px;
            }

            .search input {
                height: 44px;
                line-height: 44px;
                width: 90%;
                outline: none;
                border-radius: 0;
                border: none;
            }

            .box {
                display: flex;
                justify-content: space-between;
                padding: 30px 10px 0 10px
            }

            .dropdown-toggle {
                border: none
            }

            .list {
                margin-top: 20px;
            }

            .list .item {
                display: flex;
                border: 1px solid #F7F7F7;
                margin-bottom: 10px;
            }

            .list .item .hospital {
                padding-top: 19px;
                padding-bottom: 10px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .list .item .hospital .name {
                font-size: 16px;
                color: #333333
            }

            .list .item .hospital .enlarge {
                background: url("/img/enlarge.png") no-repeat center left;
                background-size: 16px 16px;
                padding-left: 20px;
            }

            .fo {
                text-align: center;
                line-height: 40px;
            }
        </style>
    </head>
    <body class="hold-transition skin-green">
    <?php $this->beginBody() ?>
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
                                    src="<?= \yii\helpers\Url::to('doctors/qr?text=' . urlencode($v['qrcode'])) ?>"/>
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
                    ]);
                    ?>
                    <img src="<?= \yii\helpers\Url::to('doctors/qr?text=' . urlencode($v['qrcode'])) ?>" width="320"
                         height="320"/>
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
    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage() ?>