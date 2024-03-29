<?php
/* @var $form yii\widgets\ActiveForm */

use common\models\HospitalAppoint;

$this->title='专病预约';
?>
<div class="appoint">
    <?php $form = \yii\widgets\ActiveForm::begin(['action'=>['userid'=>$doctor['userid']]]); ?>
    <?=$form->field($appoint,'type')->hiddenInput(['value'=>13])->label(false)?>
    <?=$form->field($appoint,'doctorid')->hiddenInput(['value'=>$doctor['userid']])->label(false)?>

    <?= $form->field($user, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'id_card')->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'phone')->textInput(['maxlength' => true]) ?>
   
    <?=$form->field($appoint,'vaccine')->dropDownList([''=>'请选择']+HospitalAppoint::$typeTmpText[13])->label('预约项目')?>

    <div class="appoint_day">

        <div class="item">
            <div class="title">请选择日期</div>
            <div class="days">
                <?php
                $dweek=['日','一','二','三','四','五','六'];
                foreach ($days as $k => $v) { ?>
                    <item class="rs <?= $day == $v['date'] ? 'on' : '' ?>" date="<?= date('Y-m-d', $v['date']) ?>" time="<?= $v['date'] ?>">
                        <div class="week"><?= $dweek[$v['week']] ?></div>
                        <div class="day"><?= $v['day'] ?></div>
                    </item>
                <?php } ?>
            </div>
            <?=$form->field($appoint,'appoint_date')->hiddenInput()->label(false)?>

            <div class="time">

            </div>
            <?=$form->field($appoint,'appoint_time')->hiddenInput()->label(false)?>

        </div>
    </div>

    <div class="form-group">
        <?= \yii\helpers\Html::submitButton($model->isNewRecord ? '提交' : '提交', ['id'=>'but','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


   <?php \yii\widgets\ActiveForm::end()?>
</div>
<div class="appoint_my"><a href="/zappoint/my"><img src="/img/appoint_my.png" width="56" height="56"></a></div>

<?php
$date_day = date('Y-m-d', $day);
$updateJs = <<<JS

  jQuery('#appoint-appoint_date').val({$day});

var type=[
        '08：00-09：00','09：00-10：00','10：00-11：00','13：00-14：00','14：00-15：00','15：00-16：00',
        '08：00-08：30','08：30-09：00','09：00-09：30','09：30-10：00','10：00-10：30','10：30-11：00',
        '13：00-13：30','13：30-14：00','14：00-14：30','14：30-15：00','15：00-15：30','15：30-16：00'
    ];

function select_time(day){
    jQuery.get('/zappoint/day-num?doctorid={$doctor['userid']}&day='+day,function(e) {
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

JS;
$this->registerJs($updateJs);

?>
