<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/4/20
 * Time: 上午11:38
 */
$this->title = '成人疫苗接种预约';
frontend\assets\DateAsset::register($this);

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
                <input type="text" id="select_0" class="appoint_input"  name="birthday" readonly  placeholder="请选择出生日期"  />

            </div>
        </div>
        <div class="item">
            <div class="title">联系电话</div>
            <div class="input">
                <input name="phone"
                       class="appoint_input"
                       value="<?= $user['phone'] ?>"
                       placeholder="请输入您的手机号" >
            </div>
        </div>

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

jQuery("#vaccine").change(function(e){
    var vid=jQuery("#vaccine").val();
    var sid=jQuery("#street").val();
    
    if(vid==64){
        if(!confirm("此预约通道为本市户籍60岁以上老年人免费流感疫苗（出生日期需在1961年12月31日前）预约通道，请确认")){
            return false;
        }
    }
    if(vid==73){
        if(!confirm("此预约通道为本市户籍65岁以上老年人免费流感疫苗与23价肺炎疫苗（如预约日期为2021年10月1日则出生日期需在1956年10月1日前）预约通道，请确认")){
            return false;
        }
    }
    if(vid==67){
        if(!confirm("此预约通道为本市户籍65岁以上老年人免费23价肺炎疫苗（如预约日期为2021年10月1日则出生日期需在1956年10月1日前）预约通道，请确认")){
            return false;
        }
    }
    if(vid==43){
        if(!confirm("此疫苗接种年龄限制为9至45周岁，接种完全程三针后，不超过46周岁的生日。例如：45周岁5个月也是可以打的，但是一定按照规定时间，三针半年内接种完成，打完不能超过46周岁生日")){
            return false;
        }
    }
    console.log( jQuery("#street").length);
    if(vid && sid && jQuery("#street").length  > 0){
        window.location.replace("/wappoint/from?userid={$doctor['userid']}&vid="+vid+"&sid="+sid);
    }else if(vid && jQuery("#street").length  < 1 ){
        window.location.replace("/wappoint/from?userid={$doctor['userid']}&vid="+vid);
    }
})



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
var data={appoint_name:'请填写预约人姓名！',doctorid:'请填写预约社区！',phone:'请填写正确预约人电话!',birthday:'请填写预约人生日!',sex:'请选择预约人性别！',street:'请选择街道/社区',vaccine:'请选择疫苗！',appoint_date:'请选择预约时间！',appoint_time:'请选择预约时间段！'};
jQuery("#appoint_form").submit(data,function(e){
    var labelMap = e.data;
    	var label = '';

  // 循环验证所有text元素是否为空
	$(this).find(".appoint_input").each(function(){
	    	console.log(this.name);
	    	console.log(this.value);

        if(this.name=='phone'){
            var reg =/^0?1[3|4|5|6|7|8|9][0-9]\d{8}$/;
            if(!reg.test(this.value)){
                label = labelMap[this.name];
                return false;
            }
        }
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
JS;
$this->registerJs($updateJs);

?>