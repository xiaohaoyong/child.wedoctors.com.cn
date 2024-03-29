<?php
/* @var $form yii\widgets\ActiveForm */
$this->title='冬病夏治三伏贴';
?>
<div class="appoint">
    <?php $form = \yii\widgets\ActiveForm::begin(['action'=>['userid'=>$doctor['userid']]]); ?>
    <?=$form->field($appoint,'type')->hiddenInput(['value'=>11])->label(false)?>
    <?=$form->field($appoint,'doctorid')->hiddenInput(['value'=>$doctor['userid']])->label(false)?>
    <?=$form->field($appoint,'appoint_date')->hiddenInput()->label(false)?>
    <?=$form->field($appoint,'appoint_time')->hiddenInput()->label(false)?>

    <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'gender')->dropDownList([1=>'男',2=>'女'],['prompt'=>'请选择']) ?>
    <?= $form->field($user, 'id_card')->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($appointOrder, 'type')->dropDownList(\common\models\AppointOrder2::$typeText,['prompt'=>'请选择']) ?>
    <?= $form->field($appointOrder, 'zhenduan')->dropDownList(\common\models\AppointOrder2::$zhenduanText,['prompt'=>'请选择']) ?>
    <?= $form->field($appointOrder, 'bingcheng')->textInput(['maxlength' => true]) ?>
    <?= $form->field($appointOrder, 'jiazushu')->textInput(['maxlength' => true]) ?>
    <?= $form->field($appointOrder, 'field1')->textInput(['maxlength' => true]) ?>
    <?= $form->field($appointOrder, 'field2')->textInput(['maxlength' => true]) ?>
    <?= $form->field($appointOrder, 'field3')->textInput(['maxlength' => true]) ?>
    <?= $form->field($appointOrder, 'field4')->textInput(['maxlength' => true]) ?>
    <?= $form->field($appointOrder, 'field5')->textInput(['maxlength' => true]) ?>
    <?= $form->field($appointOrder, 'field6')->dropDownList(\common\models\AppointOrder2::$field6Text,['prompt'=>'请选择']) ?>
    <?= $form->field($appointOrder, 'field7')->textInput(['maxlength' => true]) ?>
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

    <style>
        .rad {
            color: rgba(240, 85, 70, 1);
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
    <div class="form-group button">
        <?= \yii\helpers\Html::submitButton($model->isNewRecord ? '提交' : '提交', ['id'=>'but']) ?>
    </div>


   <?php \yii\widgets\ActiveForm::end()?>
</div>
<div class="appoint_my"><a href="/sappoint/my"><img src="/img/appoint_my.png" width="56" height="56"></a></div>

<?php
$date_day = date('Y-m-d', $firstday);
$updateJs = <<<JS

  jQuery('#appoint-appoint_date').val({$firstday});

var type=[
        '08:00-09:00','09:00-10:00','10:00-11:00','13:00-14:00','14:00-15:00','15:00-16:00',
        '08:00-08:30','08:30-09:00','09:00-09:30','09:30-10:00','10:00-10:30','10:30-11:00',
        '13:00-13:30','13:30-14:00','14:00-14:30','14:30-15:00','15:00-15:30','15:30-16:00'
    ];

function select_time(day){
    jQuery.get('/sappoint/day-num?doctorid={$doctor['userid']}&day='+day,function(e) {
      var times=e.times;
      var html='';
      jQuery.each(times,function(i,item){
          html=html+'<div class="rs '+(item>0?'ton':'')+'" id="'+i+'">'+type[parseInt(i)-1]+'  '+(item>0?'有号':'无号')+'</div>';
      });
      jQuery('.time').html(html);
      jQuery(".ton").bind("click",function(){
          jQuery(".ton").removeClass('a');
          jQuery(this).addClass('a');
            jQuery('#appoint-appoint_time').val(jQuery(this).attr('id'));
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
    jQuery('#appoint-appoint_date').val(jQuery(this).attr('time'));
    select_time(day);
});
var data={appoint_date:'请选择预约时间！',appoint_time:'请选择预约时间段！'};
jQuery("#w0").submit(data,function(e){
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
	$("#but").attr('disabled',true);

});
JS;
$this->registerJs($updateJs);

?>
