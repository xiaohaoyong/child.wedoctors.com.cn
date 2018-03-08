<?php
use yii\helpers\Html;

\databackend\assets\AppAsset::register($this);

$this->beginPage();

$error = Yii::$app->getSession()->getFlash('error');
$error = !empty($error) ? $error : '';
?>
    <!DOCTYPE html>
    <!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
    <!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
    <!--[if !IE]><!-->
    <html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <title><?=\Yii::$app->params['site-title']?></title>
        <meta charset="utf-8"/>
        <?= Html::csrfMetaTags() ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <?php $this->head() ?>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="/metronic/layout/css/login-soft.css" rel="stylesheet" type="text/css"/>
        <link rel="shortcut icon" href="/favicon.ico"/>
    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body class="login">
    <!-- BEGIN LOGO -->
    <div class="logo"><?=\Yii::$app->params['site-title']?></div>
    <!-- END LOGO -->
    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
    <div class="menu-toggler sidebar-toggler">
    </div>
    <!-- END SIDEBAR TOGGLER BUTTON -->
    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <?php
        $form = \yii\widgets\ActiveForm::begin([
            'class' => 'login-form',
            'fieldConfig' => [
                'labelOptions' => [
                    'class' => 'control-label visible-ie8 visible-ie9'
                ],
                'inputOptions' => [
                    'class' => 'form-control placeholder-no-fix',
                    'autocomplete' => 'off',
                ]
            ]
        ])
        ?>
        <h3 class="form-title">请输入您的管理账号</h3>
        <?php if($error){?>
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <span><?= $error ?></span>
            </div>
        <?php }?>
        <?= $form->field($model,'username',['template' => "\n{label}\n<div class=\"input-icon\">\n<i class=\"fa fa-user\"></i>\n{input}\n</div>\n"])->textInput()->label('用户名:') ?>
        <?= $form->field($model,'password',['template' => "\n{label}\n<div class=\"input-icon\">\n<i class=\"fa fa-lock\"></i>\n{input}\n</div>\n"])->passwordInput()->label('密码:') ?>
        <div class="form-actions">
            <?= \yii\helpers\Html::activeCheckbox($model,'rememberMe',['label' => '记住我']) ?>
            <?= Html::submitButton('登录 <i class="m-icon-swapright m-icon-white"></i>',[
                'class' => 'btn blue pull-right',
            ]) ?>
        </div>
        <?php \yii\widgets\ActiveForm::end() ?>

        <!-- END LOGIN FORM -->
    </div>
    <!-- END LOGIN -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
    <script src="/metronic/plugins/respond.min.js"></script>
    <script src="/metronic/plugins/excanvas.min.js"></script>
    <![endif]-->
    <script src="/metronic/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="/metronic/layout/scripts/login-soft.js" type="text/javascript"></script>
    <script src="/metronic/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
    <?php $this->endBody() ?>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
//        Login.init();
            Demo.init();
            // init background slide images
            $.backstretch([
                    "/metronic/images/1.jpg",
                    "/metronic/images/2.jpg",
                    "/metronic/images/3.jpg",
                    "/metronic/images/4.jpg"
                ], {
                    fade: 1000,
                    duration: 8000
                }
            );
        });
    </script>

    <!-- END JAVASCRIPTS -->
    </body>
    <!-- END BODY -->
    </html>
<?php $this->endPage() ?>