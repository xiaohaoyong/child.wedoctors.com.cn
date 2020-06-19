<?php
frontend\assets\WebAsset::register($this);
$this->title = "预约成人疫苗"
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
        </style>
    </head>
    <body class="hold-transition skin-green">
    <?php $this->beginBody() ?>
    <?= \common\widgets\Alert::widget() ?>
    <?=$content?>
    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage() ?>