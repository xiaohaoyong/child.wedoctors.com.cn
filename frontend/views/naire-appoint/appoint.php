<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $qn->title;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/6/19
 * Time: 下午3:57
 */
$this->title='选择检测日期、时间';
?>
<?php $form = ActiveForm::begin([
        "action"=>"/naire-appoint/save"
]); ?>
<div class="appoint">
    <input type="hidden" class="appoint_input" value="<?= $doctor['userid'] ?>" name="doctorid">
    <input type="hidden" class="appoint_input" value="<?=$qid?>" name="qid">
    <input type="hidden" class="appoint_input" id="appoint_time" value="0" name="appoint_time">
    <input type="hidden" class="appoint_input" value="<?= $firstday ?>" name="appoint_date" id="appoint_date">
    <div class="appoint_day">
        <div class="item">
            <div class="title">选择检测日期、时间</div>
            <div class="days">
                <?php
                $dweek = ['日', '一', '二', '三', '四', '五', '六'];
                foreach ($days as $k => $v) { ?>
                    <div class="rs <?= $firstday == $v['date'] && $v['dateState']==1 ? 'on' : '' ?><?= !$v['dateState'] ? 'notOp' : '' ?>" data-id="<?=$v['dateState']?>" date="<?= date('Y-m-d', $v['date']) ?>" time="<?= $v['date'] ?>">
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
</div>
<hr>
<div style="padding: 0 20px;">
    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','style'=>'background-color:#e56659;border-color:#e56659;']) ?>
</div>
<hr>
<?php ActiveForm::end(); ?>

<?php
$date_day = date('Y-m-d', $firstday);
$vid = Yii::$app->request->get('vid');
$updateJs = <<<JS


function select_time(day){
    jQuery.get('/naire-appoint/day-num?doctorid={$doctor['userid']}&day='+day,function(e) {
      var times=e.list;
      
      if(times.length<1){
          var html=e.text;
      }else{
          var html='';
          jQuery.each(times,function(i,item){
              var txt="";
              if(item.num1==0){
                  txt='无号';
              }else if(item.num1>0 && item.num==0){
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
var data={appoint_date:'请选择预约时间！',appoint_time:'请选择预约时间段！'};
jQuery("#w0").submit(data,function(e){
    console.log('123');
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
JS;
$this->registerJs($updateJs);

?>