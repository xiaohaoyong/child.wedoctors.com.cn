<?php
/* @var $form yii\widgets\ActiveForm */

use common\models\HospitalAppoint;
\common\assets\JqAlert::register($this);

$this->title='专病预约';
?>
<div class="appoint">
    <?php $form = \yii\widgets\ActiveForm::begin(['action'=>['userid'=>$doctor['userid']]]); ?>
    <?=$form->field($appoint,'type')->hiddenInput(['value'=>13])->label(false)?>
    <?=$form->field($appoint,'doctorid')->hiddenInput(['value'=>$doctor['userid']])->label(false)?>

    <?= $form->field($user, 'name',['options'=>['class'=>'item']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'id_card',['options'=>['class'=>'item']])->textInput(['maxlength' => true]) ?>
    <?= $form->field($user, 'phone',['options'=>['class'=>'item']])->textInput(['maxlength' => true]) ?>
   
    <?=$form->field($appoint,'vaccine',['options'=>['class'=>'item']])->dropDownList([''=>'请选择']+$experts)->label('请选择科室')?>



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

    <div class="form-group">
        <?= \yii\helpers\Html::submitButton($model->isNewRecord ? '提交' : '提交', ['id'=>'but','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


   <?php \yii\widgets\ActiveForm::end()?>
</div>
<div class="appoint_my"><a href="/zappoint/my"><img src="/img/appoint_my.png" width="56" height="56"></a></div>

<?php
$date_day = date('Y-m-d', $firstday);
$updateJs = <<<JS

  jQuery('#appoint-appoint_date').val({$day});

var type=[
        '08：00-09：00','09：00-10：00','10：00-11：00','13：00-14：00','14：00-15：00','15：00-16：00',
        '08：00-08：30','08：30-09：00','09：00-09：30','09：30-10：00','10：00-10：30','10：30-11：00',
        '13：00-13：30','13：30-14：00','14：00-14：30','14：30-15：00','15：00-15：30','15：30-16：00'
    ];

function select_time(day){
    jQuery.get('/zappoint/day-num?doctorid={$doctor['userid']}&day='+day+'&vid={$vid}',function(e) {
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
  jQuery('#appoint-appoint_date').val(jQuery(this).attr('time'));
  select_time(day);
});


jQuery("#appoint-vaccine").change(function(e){
    
    var vid=jQuery("#appoint-vaccine").val();
    jQuery.get('/zappoint/expert?e='+vid,function(e) {
        
        jQuery.confirm({
            title: '预约科室介绍',
            content: e.view,
            type: 'green',
            buttons: {
                ok: {
                    text: "确认",
                    btnClass: 'btn-success',
                    keys: ['enter'],
                    action: function(){
                        window.location.replace("/zappoint/from?userid={$doctor['userid']}&vid="+vid);
                    }
                },
                cancel: {
                    text: "取消",
                    btnClass: 'btn-danger',
                    keys: ['enter'],
                },
            }
        });
    })

    


})

JS;
$this->registerJs($updateJs);

?>
