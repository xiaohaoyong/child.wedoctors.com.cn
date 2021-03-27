<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '儿宝宝医生后台';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

    <div class="login-box">
        <div class="login-logo">
            <a href="#">儿宝宝医生后台</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">儿宝宝医生后台</p>

            <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

            <?= $form
                ->field($model, 'phone', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => '请输入手机号']) ?>

            <?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('验证码')]) ?>
            <?= Html::buttonInput(Yii::t('app', '获取验证码'), ['class' => 'btn bg-yellow ', 'name' => 'signup-button', 'id' => 'second']) ?>
            <div class="clear"></div>
            <div class="row">
                <div class="col-xs-8">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <?= Html::submitButton('登陆', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
                </div>
                <!-- /.col -->
            </div>


            <?php ActiveForm::end(); ?>


        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
<?php
$updateJs = <<<JS
    var v = getCookieValue("secondsremained_login") ? getCookieValue("secondsremained_login") : 0;//获取cookie值  
    if(v>0){  
        settime($("#second"));//开始倒计时  
    }  
    $('#second').on('click', function () {
        var phone=$('#loginform-phone').val();
        console.log(phone);
        $.get('/site/code?phone='+phone+'&type=1',function(e){
            if(e.code==10000){
               alert('发送成功');
               addCookie("secondsremained_login",60,60);//添加cookie记录,有效时间60s  
               setTimeout(function() { settime($('#second')) },1000) //每1000毫秒执行一次}  
            }else{
                alert('请输入正确手机号码');
            }
        },'json');
        
    });
    function settime(obj) {  
        countdown=getCookieValue("secondsremained_login") ? getCookieValue("secondsremained_login") : 0;  
        if (countdown ==0) {  
            obj.removeAttr("disabled");  
            obj.val("获取验证码");  
            return;  
        } else {  
            obj.attr("disabled", true);  
            obj.val(countdown + "秒后重发");  
            countdown--;  
            editCookie("secondsremained_login",countdown,countdown+1);
        }
        setTimeout(function() { settime($('#second')) },1000) //每1000毫秒执行一次
    }
    function getCookieValue(name){  
        var strCookie=document.cookie;  
        var arrCookie=strCookie.split("; ");  
        for(var i=0;i<arrCookie.length;i++){  
            var arr=arrCookie[i].split("=");  
              if(arr[0]==name){   
                 return unescape(arr[1]);  
               break;  
            }  
        }
    } 
    function addCookie(name,value,expiresHours){      
        var cookieString=name+"="+escape(value);      
        //判断是否设置过期时间,0代表关闭浏览器时失效  
        if(expiresHours>0){  
            var date=new Date();  
            date.setTime(date.getTime()+expiresHours*1000);  
            cookieString=cookieString+";expires=" + date.toUTCString();  
        }    document.cookie=cookieString;  
    }
    function editCookie(name,value,expiresHours){      
        var cookieString=name+"="+escape(value);   
        if(expiresHours>0){  
        var date=new Date();  
        date.setTime(date.getTime()+expiresHours*1000); //单位毫秒  
            cookieString=cookieString+";expires=" + date.toGMTString();  
        }    document.cookie=cookieString;  
    }  
JS;
$this->registerJs($updateJs);
?>