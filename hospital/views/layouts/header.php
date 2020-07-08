<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">儿宝宝</span><span class="logo-lg">儿宝宝健康云管理</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

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
                <li class="dropdown messages-menu">
                    <a href="/site/hospitals" class="dropdown-toggle">切换社区</a>
                </li>

                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <?php if($Unread){?>
                        <span class="label label-warning"><?=$Unread?></span>
                        <?php }?>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header" style="text-align: center;"><?=$Unread?"您有".$Unread."条未读消息":"没有未读消息"?></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php
                                    $showList=$showList?$showList:[];
                                    $msglist=\common\models\HospitalMail::find()
                                        ->where(['in','touser',[0,\Yii::$app->user->identity->hospitalid]])
                                        ->andWhere(['not in','id',$showList])
                                        ->all();
                                    if($msglist){
                                        foreach($msglist as $k=>$v){
                                ?>
                                            <li><!-- start message -->
                                                <a href="/hospital-mail" style="white-space: unset;">
                                                    <div style="margin: 0;"><?=mb_substr($v->content,0,15,'utf-8')?>...</div>
                                                    <p style="margin: 0;"><?=date('Y/m/d H:i',$v->createtime)?></p>
                                                </a>
                                            </li>
                                <?php }}?>
                            </ul>
                        </li>
                        <li class="footer"><?= Html::a(
                                '查看全部消息',
                                ['/hospital-mail']
                            ) ?></li>
                    </ul>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php
                        $doctor=\common\models\UserDoctor::findOne(['hospitalid'=>Yii::$app->user->identity->hospitalid]);
                        ?>
                        <span class="hidden-xs"><?=$doctor->name?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="separator" class="divider"></li>
                        <li>
                            <?= Html::a(
                                '编辑信息',
                                ['/user-doctor/update']
                            ) ?>
                        </li>
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
