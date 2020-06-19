<?php
frontend\assets\AppAsset::register($this);
$this->title = "社区医院查询"
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
        <?= \yii\helpers\Html::csrfMetaTags() ?>
        <title><?= \yii\helpers\Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <style>
            .content-wrapper {
                padding: 0 16px;
            }
            input{
                width: 50px;height: 20px;
            }
        </style>
    </head>
    <body class="hold-transition skin-green">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <div class="content-wrapper" style="line-height: 40px;">
            <div style="text-align: center;line-height: 100px;font-size: 20px;">新型冠状病毒肺炎流行病学史调查表</div>
            <div>1. 您24小时内有无发热（腋下体温 > 37.0℃）</div>
            <div><?php echo \yii\helpers\Html::checkboxList('ask[]',null,[0=>'否',1=>'是'])?></div>
            <div>2. 14天内是否有中高风险地区（境外）旅行史或者旅居史</div>
            <div><?php echo \yii\helpers\Html::checkboxList('ask[]',null,[0=>'否',1=>'是'])?></div>
            <div>3. 14天内是否有接触来自中高风险地区（境外）的发热或呼吸道症状患者</div>
            <div><?php echo \yii\helpers\Html::checkboxList('ask[]',null,[0=>'否',1=>'是'])?></div>
            <div>4. 14天内是否有聚集性发热病例接触史</div>
            <div><?php echo \yii\helpers\Html::checkboxList('ask[]',null,[0=>'否',1=>'是'])?></div>
            <div>5. 14天内是否有新型冠状病毒肺炎确诊或疑似病例接触史</div>
            <div><?php echo \yii\helpers\Html::checkboxList('ask[]',null,[0=>'否',1=>'是'])?></div>
            <div>6. 今日体温<?php echo \yii\helpers\Html::textInput('field1')?>℃</div>
            <div>以上信息填写"是"者，请您前往就近二级以上医疗机构发热门诊筛查，感谢您的配合！</div>
            <div>日期：2020年06月19日</div>
        </div>
    </div>
    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage() ?>