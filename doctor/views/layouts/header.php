<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">儿宝宝</span><span class="logo-lg">儿宝宝医生后台</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <?php
            $showList=\common\models\HospitalMailShow::find()->select('mailid')
                ->where(['hospitalid'=>\Yii::$app->user->identity->hospitalid])
                ->column();
            $Unread = \common\models\HospitalMail::find()
                ->where(['in','touser',[0,\Yii::$app->user->identity->hospitalid]])
                ->andWhere(['not in','id',$showList])
                ->count();
            ?>
            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><?=Yii::$app->user->identity->phone?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="separator" class="divider"></li>

                        <li role="separator" class="divider"></li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <?= Html::a(
                                    '退出',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
