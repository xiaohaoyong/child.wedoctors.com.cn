<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/4/20
 * Time: 上午11:38
 */
$this->title = '成人疫苗接种预约';
frontend\assets\DateAsset::register($this);
\common\assets\JqFileUpload::register($this);
\common\assets\JqAlert::register($this);

?>

<div class="appoint">
    <form name="appoint" id="appoint_form" action="/wappoint/save" method="post" enctype="multipart/form-data">
        <input name="_csrf-frontend"

               type="hidden"

               id="_csrf-frontend"

               value="<?= Yii::$app->request->csrfToken ?>">
        <input name="source" class="appoint_input"  type="hidden" value="<?=$source?>">
        <?php if($source=='xiaoxiong'){?>
            <input name="phone" type="hidden" value="<?=$user['phone']?$user['phone']:1?>">
            <input name="xuserid" type="hidden" value="<?=$xuserid?>">
            <input name="skuid" type="hidden" value="<?=$skuid?>">
        <?php }?>

        <div class="item">
            <div class="title">预约社区</div>
            <div class="input">
                <input disabled="disabled" placeholder="<?= $doctor['hospital'] ?>">
                <input type="hidden" class="appoint_input" value="<?= $doctor['userid'] ?>" name="doctorid" id="doctorid">
                <input type="hidden" class="appoint_input" value="4" name="type">
                <input type="hidden" class="appoint_input" id="appoint_time" value="0" name="appoint_time">
                <input type="hidden" class="appoint_input" value="<?= $firstday ?>" name="appoint_date" id="appoint_date">
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
        <?php if ($streets) { ?>
            <div class="item">
                <div class="title">选择街道/社区</div>
                <div class="input">
                    <select name="street" class="appoint_input" id="street">
                        <option value="0">请选择</option>
                        <?php
                        foreach ($streets as $k => $v) { ?>
                            <option value="<?= $v['id'] ?>" <?= $v['id'] == Yii::$app->request->get('sid') ? "selected" : "" ?>><?= $v['name'] ?></option>
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
            <div class="title">出生日期</div>
            <div class="input">
                <input type="text" id="select_0" class="appoint_input" value="<?=$user['birthday']?>"  name="birthday" readonly  placeholder="请选择出生日期"  />

            </div>
        </div>
        <?php if($doctor['userid'] == 38 && in_array(Yii::$app->request->get('vid'),[142,140,139,138,137,136,135,133,80,74,73,70,69,66,65,64,63,46])){ ?>
        <div class="item">
            <div class="title">居住证明</div>
            <input type='hidden'name="image" class="appoint_input"  id='text-field'  />

            <div class="upload-box1"></div>
            <div class="input">
                注：成人自费流感线上预约途径限在白纸坊街道居住、工作或上学的居民，预约前需上传本人有效凭证。非白纸坊居民可持本人医保卡等有效证件至现场进行登记预约。
            </div>
        </div>
        <?php }?>
        <?php if($doctor['userid']==747897 ){ ?>
        <div class="item">
            <div class="title">身份证号</div>

            <div class="input">
                <input name="place" class="appoint_input" value="<?= $user['place'] ?>" placeholder="请输入身份证号">
            </div>
        </div>
        <?php }?>
        <?php
        if (!$vaccines || Yii::$app->request->get('vid')) {
            ?>
            <div class="appoint_day">
                <div class="item">
                    <div class="title">请选择日期</div>
                    <div class="days">
                        <?php
                        $dweek = ['日', '一', '二', '三', '四', '五', '六'];
                        foreach ($days as $k => $v) { ?>
                            <div class="rs <?= $firstday == $v['date'] && $v['dateState']==1 ? 'on' : '' ?><?= !$v['dateState'] ? 'notOp' : '' ?>" date="<?= date('Y-m-d', $v['date']) ?>" time="<?= $v['date'] ?>">
                                <div class="week"><?= $dweek[$v['week']] ?></div>
                                <div class="day"><?= $v['day'] ?></div>
                                <div class="msg"><?=$v['dateMsg']?></div>

                            </div>
                        <?php } ?>
                    </div>
                    <div class="time">

                    </div>

                </div>
            </div>
        <?php } ?>

        <style>
            .rad {
                color: rgba(240, 85, 70, 1);
                font-size: 14px;
                margin-top: 10px;
            }
        </style>
        <div class="button">
            <button type="submit">确定预约</button>
            <div class="rad">注：请仔细阅读温馨提示，并确认是否可在本社区接种后预约</div>
        </div>
    </form>
</div>
<div class="appoint_my"><a href="/wappoint/my"><img src="/img/appoint_my.png" width="56" height="56"></a></div>

<?php
$date_day = date('Y-m-d', $firstday);
$vid = Yii::$app->request->get('vid');
$sid = Yii::$app->request->get('sid');

$updateJs = <<<JS

     jQuery.selectYY_MM_DD("#select_0");

jQuery(".upload-box1").ajaxImageUpload({
    fileInput: 'img', //上传按钮名，即input[type=file]的name值
    postUrl: '/wappoint/upload', //上传的服务器地址
    error:function(e){
        jQuery.alert(e.msg)
    },
    success:function(e){
        jQuery("#text-field").val(e.src);
    },
    delete:function(e){
        jQuery("#text-field").val('');
    }
});

jQuery("#vaccine").change(function(e){
    var vid=jQuery("#vaccine").val();
    var sid=jQuery("#street").val();
    var doctorid=jQuery("#doctorid").val();
    var content='';
    if(vid==64){
         content = "此预约通道为本市户籍60岁以上老年人免费流感疫苗（出生日期需在1963年12月31日前）预约通道，请确认";       
    }
    if(vid==73){
         content = "此预约通道为本市户籍65岁以上老年人免费流感疫苗与23价肺炎疫苗（如预约日期为2021年10月1日则出生日期需在1956年10月1日前）预约通道，请确认";
    }
    if(vid==67){
         content = "此预约通道为本市户籍65岁以上老年人免费23价肺炎疫苗（如预约日期为2021年10月1日则出生日期需在1956年10月1日前）预约通道，请确认";
    }
    const hpvid=['45' , '57' , '58' , '59' , '97' ,'117','114','78','51','50','43','44','54','55','56','98'];
    if(hpvid.indexOf(vid)>-1 && doctorid!=184741){
         content = "年龄为9至45周岁，接种完全程三针后，不得超过46周岁生日。例如:45周岁5个月也可接种，但是得按照规定时间，半年内三针接种完成，最后一针不能超过46周岁生日。接种时请您携带医保卡及儿宝宝预约二维码，现场实名扫码核销。二维码中姓名与实际姓名不同或者预约时间不是当天不提供接种服务。";
    }
    if(vid==80){
         content = "此疫苗接种年龄限制为3岁以内，超过三岁清选择三岁以上疫苗接种，成人请勿预约";
    }

    const xinguan = ['86','87','88','89','90','91','92','93','94','95','96','104','105','106','107','108','109','110','111','112','113','117'];

    if(xinguan.indexOf(vid)>-1 && doctorid!=38){
         content = "按照目前免疫要求，感染过新冠病毒（阳过），且已经完成基础免疫（接种过两针科兴、北京生物等的灭活疫苗或3针智飞龙科马重组疫苗或1针康希诺肌注式疫苗），不再进行加强免疫（不再打第三针或者第四针）";
    }
    const xinguan1 = ['94','111','146'];
    if(xinguan1.indexOf(vid)>-1 && doctorid==4119){
         content = "如果您最后一剂新冠疫苗接种时间晚于最后一次感染时间，则需要跟接种时间间隔3-6个月；如果您最后一剂新冠疫苗接种时间早于最后一次感染时间，则需要跟感染时间间隔6个月才可接种。优先推荐含有XBB成份的新冠疫苗进行接种。";
    }



    const arr = ['45','57','58','59','97','144','145'];

    if(arr.indexOf(vid)>-1 && doctorid!=184741){
         content = "接种时请您携带身份证及儿宝宝预约二维码，幼儿园及学校学生接种须携带接种本。为防止倒号行为，现场将实名扫码核销二维码。二维码中姓名与实际姓名不同者或者预约时间不是当天者不提供接种服务。";
    }
    console.log(content);
    if(content){
        jQuery.confirm({
            title: '请确认您已知晓！',
            content: content,
            type: 'green',
            buttons: {
                ok: {
                    text: "确认知晓",
                    btnClass: 'btn-success',
                    keys: ['enter'],
                    action: function(){
                        if(vid && sid && jQuery("#street").length  > 0){
                            window.location.replace("/wappoint/from?userid={$doctor['userid']}&vid="+vid+"&sid="+sid);
                        }else if(vid && jQuery("#street").length  < 1 ){
                            window.location.replace("/wappoint/from?userid={$doctor['userid']}&vid="+vid);
                        }
                    }
                },
                cancel: {
                    text: "取消",
                    btnClass: 'btn-danger',
                    keys: ['enter'],
                },
            }
        });
        return false;
    }else{
        if(vid && sid && jQuery("#street").length  > 0){
            window.location.replace("/wappoint/from?userid={$doctor['userid']}&vid="+vid+"&sid="+sid);
        }else if(vid && jQuery("#street").length  < 1 ){
            window.location.replace("/wappoint/from?userid={$doctor['userid']}&vid="+vid);
        }
    }    
})
    const arr = ['45','57','58','59','97'];
     var vid=jQuery("#vaccine").val();
    var sid=jQuery("#street").val();
        var doctorid=jQuery("#doctorid").val();

    if(arr.indexOf(vid)>-1 && doctorid==184741){
        var content = "如遇约满请周一到周五下午1-5点拨打010-67527884咨询。谢谢";
        vaccineConfirm(content,vid,sid);
    }

function vaccineConfirm(content,vid,sid){
    jQuery.confirm({
            title: '温馨提醒',
            content: content,
            type: 'green',
            buttons: {
                ok: {
                    text: "确认知晓",
                    btnClass: 'btn-success',
                    keys: ['enter']
                },
                cancel: {
                    text: "取消",
                    btnClass: 'btn-danger',
                    keys: ['enter'],
                },
            }
        });
}


jQuery("#street").change(function(e){
    var vid=jQuery("#vaccine").val();
    var sid=jQuery("#street").val();
    if(vid && sid){
        window.location.replace("/wappoint/from?userid={$doctor['userid']}&vid="+vid+"&sid="+sid);
    }
})

var type=[
        '08：00-09：00','09：00-10：00','10：00-11：00','13：00-14：00','14：00-15：00','15：00-16：00',
        '08：00-08：30','08：30-09：00','09：00-09：30','09：30-10：00','10：00-10：30','10：30-11：00',
        '13：00-13：30','13：30-14：00','14：00-14：30','14：30-15：00','15：00-15：30','15：30-16：00'
    ];

function select_time(day){
    jQuery('.button,button').attr('disabled','disabled')
    jQuery.get('/wappoint/day-num?doctorid={$doctor['userid']}&day='+day+'&vid={$vid}'+'&sid={$sid}',function(e) {
      var times=e.list;
      
      if(times.length<1){
          var html=e.text;
      }else{
          var html='';
          jQuery.each(times,function(i,item){
              var txt="";
              if(item.num1==0){
                  txt='无号';
              }else if(item.num1>0 && item.num<1){
                  txt='约满';
              }else if(item.num>0){
                  txt='有号';
              }
              html=html+'<div class="rs '+(item.num>0?'ton':'')+'" id="'+item.appoint_time+'">'+item.time+'  '+txt+'</div>';
          });
      }
      jQuery('.time').html(html);
      jQuery(".ton").bind("click",function(){
          jQuery(".ton").removeClass('a');
          jQuery(this).addClass('a');
            jQuery('#appoint_time').val(jQuery(this).attr('id'));
      });
      jQuery('.button,button').attr('disabled',false)

    });
}
select_time('{$date_day}');
jQuery(".days .rs").bind("click",function(){
    jQuery('#appoint_time').val(0);
          jQuery('.time').html('加载中...');

  jQuery(".days .rs").removeClass('on');
  jQuery(this).addClass('on');
  var day= jQuery(this).attr('date');
  jQuery('#appoint_date').val(jQuery(this).attr('time'));
  select_time(day);
});
var data={appoint_name:'请填写预约人姓名！',doctorid:'请填写预约社区！',place:'请填写身份证号码！',phone:'请填写正确预约人电话!',birthday:'请填写预约人生日!',sex:'请选择预约人性别！',street:'请选择街道/社区',vaccine:'请选择疫苗！',appoint_date:'请选择预约时间！',appoint_time:'请选择预约时间段！',image:'请上传居住证明'};
jQuery("#appoint_form").submit(data,function(e){
    var labelMap = e.data;
    var label = '';
    console.log(labelMap)	
    var formdata = [];
  // 循环验证所有text元素是否为空
	$(this).find(".appoint_input").each(function(){
	    	    	console.log(this.name);
	    	console.log(this.value);
	    	formdata[this.name]=this.value;
        // if(this.name=='phone'){
        //     var reg =/^0?1[3|4|5|6|7|8|9][0-9]\d{8}$/;
        //     if(!reg.test(this.value)){
        //         label = labelMap[this.name];
        //         return false;
        //     }
        // }
		if(this.value=='' || this.value==0 || this.value=='undefined'){
		    label = labelMap[this.name];
			return false;
		}
	});
	if( label ){
	    showMessage(label,3500,true,'bounceInUp-hastrans','bounceOutDown-hastrans');
	    return false;
	}
    console.log(formdata);
    if(formdata['source']!='xiaoxiong'){
        if(!formdata['phone'] || !formdata['vcode']){
            var vid=jQuery("#vaccine").val();
            if(vid == 64){
                jQuery("#modle_phone1").modal('show');
            }else{
                jQuery("#modle_phone").modal('show');
            }
            return false;
        }
    }
});




var phone_data={phone:'请填写正确手机号码！',vcode:'请填写正确验证码！'};
jQuery(".appoint_phone_form").submit(phone_data,function(e){
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
		    var reg =/^0?1[2|3|4|5|6|7|8|9][0-9]\d{8}$/;
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
	var input = '<input type="hidden" class="appoint_input"  name="phone" value="'+valueMap.phone+'"> ';
	jQuery("#appoint_form").append(input);
	var input = '<input type="hidden" class="appoint_input"  name="vcode" value="'+valueMap.vcode+'"> ';
	jQuery("#appoint_form").append(input);
    jQuery("#appoint_form").submit();
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
    'header'=>'验证/修改手机号码'
]);
?>
<div class="appoint_p">
    <form id="appoint_phone" class="appoint_phone_form">
        <div class="item">
            <div class="title">联系电话</div>
            <div class="input">
                <input name="phone" class="inputa appoint_phone" id="phone" placeholder="请填写手机号" value="<?=$user['phone']?>">
                <div class="vcode-model">
                    <div class="vcode">
                        <input name="vcode" class="inputa appoint_phone" maxlength="6" placeholder="请填写验证码" value="">
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
<?php
\yii\bootstrap\Modal::begin([
    'id' => 'modle_phone1',
    'header'=>'验证/修改手机号码'
]);
?>
<div class="appoint_p">
    <form id="appoint_phone1" class="appoint_phone_form">
        <div class="item">
            <div class="title">联系电话</div>
            <div class="input">
                <input name="phone" class="inputa appoint_phone" id="phone" placeholder="请填写手机号" value="<?=$user['phone']?>">
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
