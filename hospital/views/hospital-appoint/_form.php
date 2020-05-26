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
                        <th>
                            门诊描述（选择预约项目温馨提醒）<br>
                            门诊日期，预约注意事项等
                        </th>
                        <td><?= $form->field($model, 'info')->textarea([ 'rows'=>5])->label(false) ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
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
                    <?php if(in_array($model->type,[2,4])){
                        if($model->type==4){
                            $data=\common\models\Vaccine::find()->select('name')->where(['adult'=>1])->indexBy('id')->column();
                            $data=[-2 => '两癌筛查'] + $data;
                        }else{
                            $data=\common\models\Vaccine::find()->select('name')->where(['adult'=>0])->indexBy('id')->column();
                            $data=[0 => '全部一类疫苗', -1 => '全部二类疫苗', -2 => '两癌筛查'] + $data;
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
$updateJs = <<<JS
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