<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/4/20
 * Time: 上午11:38
 */
$this->title = '成人疫苗接种预约';

?>
<div class="appoint">
    <form name="appoint" id="appoint_form" action="/wappoint/save" method="post">
        <input name="_csrf-frontend"

               type="hidden"

               id="_csrf-frontend"

               value="<?= Yii::$app->request->csrfToken ?>">
        <div class="item">
            <div class="title">预约社区</div>
            <div class="input">
                <input disabled="disabled" placeholder="<?= $doctor['hospital'] ?>">
                <input type="hidden" class="appoint_input" value="<?= $doctor['userid'] ?>" name="doctorid">
                <input type="hidden" class="appoint_input" value="4" name="type">
                <input type="hidden" class="appoint_input" id="appoint_time" value="0" name="appoint_time">
                <input type="hidden" class="appoint_input" value="<?= $day ?>" name="appoint_date" id="appoint_date">
            </div>
        </div>
        <?php if ($vaccines) { ?>
            <div class="item">
                <div class="title">疫苗</div>
                <div class="input">
                    <select name="vaccine" class="appoint_input" id="vaccine">
                        <option value="0">请选择</option>
                        <?php
                        foreach ($vaccines as $k => $v) { ?>
                            <option value="<?= $v['id'] ?>" <?= $v['id'] == Yii::$app->request->get('vid') ? "selected" : "" ?>><?= $v['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php } ?>
        <div class="item">
            <div class="title">预约人姓名</div>
            <div class="input">
                <input name="appoint_name" class="appoint_input" value="<?= $user['name'] ?>" placeholder="请输入您的姓名">
            </div>
        </div>
        <div class="item">
            <div class="title">预约人性别</div>
            <div class="input">
                <select name="sex" class="select appoint_input form-control">
                    <option value="0">请选择您的性别</option>
                    <option value="1" <?= $user['gender'] == 1 ? 'selected' : '' ?>>男</option>
                    <option value="2" <?= $user['gender'] == 2 ? 'selected' : '' ?>>女</option>
                </select>
            </div>
        </div>
        <div class="item">
            <div class="title">联系电话</div>
            <div class="input">
                <input name="phone" readonly unselectable="on" id="appoint_from_phone" class="appoint_input"
                       value="<?= $user['phone'] ?>"
                       placeholder="请输入您的手机号" data-toggle="modal" data-target="#modle_phone">
            </div>
        </div>

        <?php
        if (!$vaccines || Yii::$app->request->get('vid')) {
            ?>
            <div class="item">
                <div class="title">请选择日期</div>
                <div class="days">
                    <?php
                    $dweek = ['日', '一', '二', '三', '四', '五', '六'];
                    foreach ($days as $k => $v) { ?>
                        <item class="rs" date="<?= date('Y-m-d', $v['date']) ?>" time="<?= $v['date'] ?>">
                            <div class="week"><?= $dweek[$v['week']] ?></div>
                            <div class="day <?= $day == $v['date'] ? 'on' : '' ?>"><?= $v['day'] ?></div>
                        </item>
                    <?php } ?>
                </div>
                <div class="time">

                </div>

            </div>
        <?php } ?>

        <style>
            .rad{color: rgba(240,85,70,1); font-size: 14px; margin-top: 10px;}
        </style>
        <div class="button">
            <button type="submit">确定预约</button>
            <div class="rad">注：请仔细阅读温馨提示，并确认是否可在本社区接种后预约</div>
        </div>
    </form>
</div>
<div class="appoint_my"><a href="/wappoint/my"><img src="/img/appoint_my.png" width="56" height="56"></a></div>

<?php
$date_day = date('Y-m-d', $day);
$vid = Yii::$app->request->get('vid');
$updateJs = <<<JS


jQuery("#vaccine").change(function(e){
    var a=jQuery("#vaccine").val();
    window.location.replace("/wappoint/from?userid={$doctor['userid']}&vid="+a);
})

var type=[
        '08：00-09：00','09：00-10：00','10：00-11：00','13：00-14：00','14：00-15：00','15：00-16：00',
        '08：00-08：30','08：30-09：00','09：00-09：30','09：30-10：00','10：00-10：30','10：30-11：00',
        '13：00-13：30','13：30-14：00','14：00-14：30','14：30-15：00','15：00-15：30','15：30-16：00'
    ];

function select_time(day){
    jQuery.get('/wappoint/day-num?doctorid={$doctor['userid']}&day='+day+'&vid={$vid}',function(e) {
      var times=e.times;
      
      if(times.length<1){
          var html=e.text;
      }else{
          var html='';
          jQuery.each(times,function(i,item){
              html=html+'<div class="rs '+(item>0?'ton':'')+'" id="'+i+'">'+type[parseInt(i)-1]+'  '+(item>0?'有号':'无号')+'</div>';
          });
      }
      jQuery('.time').html(html);
      jQuery(".ton").bind("click",function(){
          jQuery(".ton").removeClass('a');
          jQuery(this).addClass('a');
            jQuery('#appoint_time').val(jQuery(this).attr('id'));
      });
    });
}
select_time('{$date_day}');
jQuery(".days .rs").bind("click",function(){
    jQuery('#appoint_time').val(0);
          jQuery('.time').html('加载中...');

  jQuery(".days .rs .day").removeClass('on');
  jQuery(this).children('.day').addClass('on');
  var day= jQuery(this).attr('date');
  jQuery('#appoint_date').val(jQuery(this).attr('time'));
  select_time(day);
});
var data={appoint_name:'请填写预约人姓名！',doctorid:'请填写预约社区！',phone:'请填写预约人电话!',sex:'请选择预约人性别！',vaccine:'请选择疫苗！',appoint_date:'请选择预约时间！',appoint_time:'请选择预约时间段！'};
jQuery("#appoint_form").submit(data,function(e){
    var labelMap = e.data;
    	var label = '';

  // 循环验证所有text元素是否为空
	$(this).find(".appoint_input").each(function(){
	    	console.log(this.name);
	    	console.log(this.value);

		if(this.value=='' || this.value==0 || this.value=='undefined'){
		    label = labelMap[this.name];
			return false;
		}
	});
	if( label ){
	    showMessage(label,3500,true,'bounceInUp-hastrans','bounceOutDown-hastrans');
		return false;
	}
});

var phone_data={phone:'请填写正确手机号码！',vcode:'请填写正确验证码！'};
jQuery("#appoint_phone").submit(phone_data,function(e){
    var labelMap = e.data;
    var valueMap ={};
    	var label = '';
    
  // 循环验证所有text元素是否为空
	$(this).find(".appoint_phone").each(function(){
		if(this.value=='' || this.value==0 || this.value=='undefined'){
		    label = labelMap[this.name];
			return false;
		}
		if(this.name=='phone'){
		    var reg =/^0?1[3|4|5|6|7|8|9][0-9]\d{8}$/;
            if(!reg.test(this.value)){
                label = labelMap[this.name];
			    return false;
            }
		}
		valueMap[this.name]=this.value;
		
	});
	if( label ){
	    showMessage(label,3500,true,'bounceInUp-hastrans','bounceOutDown-hastrans');
		return false;
	}

    $.get('/wappoint/vphone?phone='+valueMap.phone+'&vcode='+valueMap.vcode,function(e){
        if(e.code==10000){
           showMessage('验证成功',3500,true,'bounceInUp-hastrans','bounceOutDown-hastrans');
           jQuery('#appoint_from_phone').val(valueMap.phone);
           jQuery('#modle_phone').modal('hide')
        }else{
           showMessage('验证失败',3500,true,'bounceInUp-hastrans','bounceOutDown-hastrans');
        }
    },'json');
	return false;
});


var v = getCookieValue("secondsremained_login") ? getCookieValue("secondsremained_login") : 0;//获取cookie值  
    if(v>0){  
        settime($("#second"));//开始倒计时  
    }  
    $('#second').on('click', function () {
        var phone=$('#phone').val();
        console.log(phone);
        $.get('/wappoint/code?phone='+phone+'&type=1',function(e){
            if(e.code==10000){
               showMessage('发送成功',3500,true,'bounceInUp-hastrans','bounceOutDown-hastrans');

               addCookie("secondsremained_login",60,60);//添加cookie记录,有效时间60s  
               setTimeout(function() { settime($('#second')) },1000) //每1000毫秒执行一次}  
            }else{
               showMessage('请输入正确手机号码',3500,true,'bounceInUp-hastrans','bounceOutDown-hastrans');
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

<?php
\yii\bootstrap\Modal::begin([
    'id' => 'modle_phone',
    'header' => '验证/修改手机号码'
]);
?>
<div class="appoint_p">
    <form id="appoint_phone">
        <div class="item">
            <div class="title">联系电话</div>
            <div class="input">
                <input name="phone" class="inputa appoint_phone" id="phone" placeholder="请填写手机号" value="">
                <div class="vcode-model">
                    <div class="vcode">
                        <input name="vcode" class="inputa appoint_phone" placeholder="请填写验证码" value="">
                    </div>
                    <div class="botton">
                        <?= \yii\bootstrap\Html::buttonInput(Yii::t('app', '获取验证码'), ['class' => 'btn btn-warning', 'name' => 'signup-button', 'id' => 'second']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="button">
            <button type="submit">确定</button>
        </div>
    </form>
</div>
<?php
\yii\bootstrap\Modal::end();
?>
