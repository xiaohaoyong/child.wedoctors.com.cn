<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HospitalAppoint */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    @media screen and (max-width: 1450px) {
        .select2-selection__rendered{max-width: 165px;}
    }
</style>
<div class="hospital-appoint-form">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">

                <?php
                $form = ActiveForm::begin(); ?>
                <table id="w0" class="table table-striped table-bordered detail-view">
                    <tbody>
                    <tr>
                        <th>周期长度</th>
                        <td><?= $form->field($model, 'cycle', ['options' => ['class' => "col-xs-3"]])->dropDownList(\common\models\UserDoctorAppoint::$cycleText, ['prompt' => '请选择'])->label(false) ?></td>
                    </tr>
                    <tr>
                        <th>
                            延迟日期<br>
                            注：0为次日可预约，1为后天以此类推
                        </th>
                        <td><?= $form->field($model, 'delay', ['options' => ['class' => "col-xs-3"]])->textInput()->label(false) ?>
                            天
                        </td>
                    </tr>
                    <tr><th>门诊时间</th><td><?= $form->field($model, 'week')->checkboxList([
                                '1' => '周一  ',
                                '2' => '周二  ',
                                '3' => '周三  ',
                                '4' => '周四  ',
                                '5' => '周五  ',
                                '6' => '周六  ',
                                '0' => '周日  ',
                            ],['class'=>'flat-red'])->label(false) ?></td></tr>
                    <tr><th>预约时间段<br>注：设置后立即生效<br>（如在4月2日修改为半点号，周期长度为2周，4月17日前已经有家长<br>预约则还是按照之前的时间段显示，如没有预约则按照新设置的显示）</th><td><?= $form->field($model, 'interval')->radioList([
                                '1' => '一小时  ',
                                '2' => '半小时  '
                            ],['class'=>'flat-red',
                            ])->label(false) ?>
                        </td></tr>

                    <tr>
                        <th>
                            可预约日期<br>
                            如需要在周末，节假日，或者串休日开通门诊，则需要单独在此处添加门诊日期

                            <?php
                            $sdate=strtotime(date('Y-m-01'));
                            $edate=strtotime('+6 month',strtotime(date('Y-m-01')));
                            $days=($edate-$sdate)/86400;
                            for ($i=0;$i<$days;$i++){
                                $time=strtotime('+'.$i.' day',$sdate);
                                $dayList[date('Y-m-d',$time)]=date('Y-m-d',$time);
                            }
                            ?>
                        </th>
                        <td><?= $form->field($model, 'sure_date', ['options' => ['class' => "col-xs-5"]])->widget(\kartik\select2\Select2::classname(), [
                                'data' => $dayList,
                                'language' => 'de',
                                'options' => ['placeholder' => '请选择','multiple'=>'multiple'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label(false)?>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            放号时间设置<br>
                            注：新放号日期将会在该时间后才可预约
                        </th>
                        <td><?= $form->field($model, 'release_time', ['options' => ['class' => "col-xs-3"]])->dropDownList(\common\models\HospitalAppoint::$rtText)->label(false) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            科室电话
                        </th>
                        <td><?= $form->field($model, 'phone', ['options' => ['class' => "col-xs-5"]])->textInput(['value'=>0])->label(false) ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?=$type?>
                            门诊描述（选择预约项目温馨提醒）<br>
                            门诊日期，预约注意事项等
                        </th>
                        <td><?= $form->field($model, 'info')->textarea([ 'rows'=>5])->label(false) ?>
                        </td>
                    </tr>
                    <?php if($type==1){?>
                    <tr>
                        <td><?= $form->field($model,'is_month')->checkbox([],false) ?>
                            <br>
                            注1：限制月龄开启后家长预约儿童体检需要选择体检月龄，系统会根据下方"体检限制月龄设置"<br>
                            和儿童生日判断是否可以预约<br>
                            注2：判断宝宝是否可约是按照宝宝出生日期和预约日期判断
                        </td>
                        <td></td>

                    </tr>
                    <?php }?>
                    </tbody>
                </table>
<?php if($type==1){?>
                <div>
                    <!-- Nav tabs -->
                    <ul id="intervalTab" class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#">体检限制月龄设置</a></li>
                    </ul>
                </div>

                <div class="box-body table-responsive no-padding" >
                    <div id="example1_wrapper1" class="dataTables_wrapper form-inline dt-bootstrap" >
                        <table id="w1" class="table table-striped table-bordered detail-view col-md-12">
                            <tbody>
                            <?php
                            foreach (\common\models\HospitalAppointMonth::$typeText as $k=>$v){
                                ?>
                                <tr>
                                    <td><?=$v?></td>
                                    <td><?=Html::checkboxList('month['.$k.']',$hospitalAppointMonth,\common\models\HospitalAppointMonth::$monthText[$k])?></td>
                                </tr>
                            <?php }?>
                            <tr>
                                <td>注：如选择2月，则宝宝出生日至今必须满2个自然月且小于3个自然月之间才可预约，
                                    如选择2月，3月则宝宝出生至今必须满2个自然月且小于4个自然月才可预约

                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div></div>
<?php }?>
                <div>
                    <!-- Nav tabs -->
                    <ul id="intervalTab" class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#">号源设置</a></li>
                    </ul>
                </div>
                <div>
                    <!-- Nav tabs -->
                    <ul id="intervalTab" class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tabyi" aria-controls="tabyi" role="tab" data-toggle="tab">一小时</a></li>
                        <li role="presentation"><a href="#tabban" aria-controls="tabban" role="tab" data-toggle="tab">半小时</a></li>
                    </ul>
                </div>
                <div class="box-body table-responsive no-padding">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <table id="w0" class="table table-striped table-bordered detail-view col-md-12">
                    <tbody>
                    <tr>
                        <td nowrap="nowrap"></td>
                        <td>星期一</td>
                        <td>星期二</td>
                        <td>星期三</td>
                        <td>星期四</td>
                        <td>星期五</td>
                        <td>星期六</td>
                        <td>星期日</td>
                    </tr>

                    <?php
                    $weeks=[1,2,3,4,5,6,0];

                    foreach(\common\models\HospitalAppointWeek::$typeText as $k=>$v){
                        if(\Yii::$app->user->identity->hospital!=110591 && in_array($k,[19,20])){
                            continue;
                        }
                        if($k<7){
                    ?>
                    <tr class="yi">
                        <td nowrap="nowrap"><?=$v?></td>
                        <td><?=Html::textInput('num[1]['.$k.']',$nums[1][$k]?$nums[1][$k]:0,['style'=>'text-align:center;'])?></td>
                        <td><?=Html::textInput('num[2]['.$k.']',$nums[2][$k]?$nums[2][$k]:0,['style'=>'text-align:center;'])?></td>
                        <td><?=Html::textInput('num[3]['.$k.']',$nums[3][$k]?$nums[3][$k]:0,['style'=>'text-align:center;'])?></td>
                        <td><?=Html::textInput('num[4]['.$k.']',$nums[4][$k]?$nums[4][$k]:0,['style'=>'text-align:center;'])?></td>
                        <td><?=Html::textInput('num[5]['.$k.']',$nums[5][$k]?$nums[5][$k]:0,['style'=>'text-align:center;'])?></td>
                        <td><?=Html::textInput('num[6]['.$k.']',$nums[6][$k]?$nums[6][$k]:0,['style'=>'text-align:center;'])?></td>
                        <td><?=Html::textInput('num[0]['.$k.']',$nums[0][$k]?$nums[0][$k]:0,['style'=>'text-align:center;'])?></td>

                    </tr>
                    <?php }}?>
                    <?php
                    foreach(\common\models\HospitalAppointWeek::$typeText as $k=>$v){
                        if($k>6){
                            ?>
                            <tr class="ban">
                                <td nowrap="nowrap"><?=$v?></td>
                                <td><?=Html::textInput('num[1]['.$k.']',$nums[1][$k]?$nums[1][$k]:0,['style'=>'text-align:center;'])?></td>
                                <td><?=Html::textInput('num[2]['.$k.']',$nums[2][$k]?$nums[2][$k]:0,['style'=>'text-align:center;'])?></td>
                                <td><?=Html::textInput('num[3]['.$k.']',$nums[3][$k]?$nums[3][$k]:0,['style'=>'text-align:center;'])?></td>
                                <td><?=Html::textInput('num[4]['.$k.']',$nums[4][$k]?$nums[4][$k]:0,['style'=>'text-align:center;'])?></td>
                                <td><?=Html::textInput('num[5]['.$k.']',$nums[5][$k]?$nums[5][$k]:0,['style'=>'text-align:center;'])?></td>
                                <td><?=Html::textInput('num[6]['.$k.']',$nums[6][$k]?$nums[6][$k]:0,['style'=>'text-align:center;'])?></td>
                                <td><?=Html::textInput('num[0]['.$k.']',$nums[0][$k]?$nums[0][$k]:0,['style'=>'text-align:center;'])?></td>
                            </tr>
                        <?php }}?>
                    <?php if(in_array($type,[2,4])){
                        if($type==4){
                            $data=\common\models\Vaccine::find()->select('name')->where(['adult'=>1])->indexBy('id')->column();
                            $data=[-2 => '两癌筛查'] + $data;
                        }else{
                            $data=\common\models\Vaccine::find()->select('name')->where(['adult'=>0])->indexBy('id')->column();
                            $data=[0 => '全部一类疫苗', -1 => '全部二类疫苗'] + $data;
                        }
                        ?>
                    <tr>
                        <td>选择疫苗</td>
                        <?php
                        ?>
                        <?php foreach ($weeks as $wk => $wv) { ?>

                            <td><?= \kartik\select2\Select2::widget([
                                    'name' => 'vaccine[' . $wv . ']',
                                    'data' => $data,
                                    'language' => 'de',
                                    'options' => ['placeholder' => '请选择', 'multiple' => 'multiple'],
                                    'showToggleAll' => false,
                                    'value' => \common\models\HospitalAppointVaccine::find()->select('vaccine')->where(['haid' => $model->id])->andWhere(['week' => $wv])->column(),
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]) ?></td>
                        <?php } ?>
                    </tr>
                    <?php }?>
                    </tbody>
                </table>
                        </div></div>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? '提交' : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
<?php
$interval=isset($model->interval)?$model->interval:1;
$is_month=isset($model->is_month)?$model->is_month:0;

$updateJs = <<<JS
var is_month={$is_month};
if(is_month==1){
    $('#w1').show();
}else{
    $('#w1').hide();
}
$('input[name="HospitalAppoint[is_month]"]').change(function(){
        var is_month=$(this).val();
        if(is_month==1){
              $('#w1').show();

        }else{
            $('#w1').hide();
        }
    });

    var default_interval={$interval};
    if(default_interval==1){
        $('.ban').hide();
        $('.yi').show();
        $('#intervalTab a:first').tab('show');
    }else{
        $('.ban').show();
        $('.yi').hide();
        $('#intervalTab a:last').tab('show');

    }
    $('input[name="HospitalAppoint[interval]"]').change(function(){
        var interval=$(this).val();
        if(interval==1){
           $('.ban').hide();
        $('.yi').show();
        $('#intervalTab a:first').tab('show');
        }else{
            $('.ban').show();
        $('.yi').hide();
        $('#intervalTab a:last').tab('show');
        }
    });
    $('#intervalTab a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
      var tab=$(this).attr('aria-controls');
      if(tab=='tabyi'){
            $('.ban').hide();
            $('.yi').show();
        }else{
            $('.ban').show();
            $('.yi').hide();
        }
    })
JS;
$this->registerJs($updateJs);
?>