<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use common\models\Vaccine;

/* @var $this yii\web\View */
/* @var $model common\models\HospitalAppoint */
/* @var $form yii\widgets\ActiveForm */
?>
    <style>
        .modal_over {
            overflow: hidden;
        }

        .modal_over .modal {
            overflow-x: hidden;
            overflow-y: auto;
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
                            <td>
                                <?= $form->field($model, 'cycle', ['options' => ['class' => "col-xs-3"]])->dropDownList(\common\models\HospitalAppoint::$cycleText, ['prompt' => '请选择'])->label(false) ?>
                                <?php if ($type == 4) {
                                    echo Html::a('扩展', '#', [
                                        'class' => 'extend btn btn-primary',
                                        'data-target' => '#extend',//关联模拟框(模拟框的ID)
                                        'data-toggle' => "modal", //定义为模拟框 触发按钮
                                    ]);
                                }
                                ?>
                            </td>
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
                        <tr>
                            <th>门诊时间</th>
                            <td><?= $form->field($model, 'week')->checkboxList([
                                    '1' => '周一  ',
                                    '2' => '周二  ',
                                    '3' => '周三  ',
                                    '4' => '周四  ',
                                    '5' => '周五  ',
                                    '6' => '周六  ',
                                    '0' => '周日  ',
                                ], ['class' => 'flat-red'])->label(false) ?></td>
                        </tr>
                        <tr>
                            <th>预约时间段<br>注：设置后立即生效<br>（如在4月2日修改为半点号，周期长度为2周，4月17日前已经有家长<br>预约则还是按照之前的时间段显示，如没有预约则按照新设置的显示）
                            </th>
                            <td><?= $form->field($model, 'interval')->radioList([
                                    '1' => '一小时  ',
                                    '2' => '半小时  '
                                ], ['class' => 'flat-red',
                                ])->label(false) ?>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                可预约日期<br>
                                如需要在周末，节假日，或者串休日开通门诊，则需要单独在此处添加门诊日期

                                <?php
                                $sdate = strtotime(date('Y-m-01'));
                                $edate = strtotime('+6 month', strtotime(date('Y-m-01')));
                                $days = ($edate - $sdate) / 86400;
                                for ($i = 0; $i < $days; $i++) {
                                    $time = strtotime('+' . $i . ' day', $sdate);
                                    $dayList[date('Y-m-d', $time)] = date('Y-m-d', $time);
                                }
                                ?>
                            </th>
                            <td><?= $form->field($model, 'sure_date', ['options' => ['class' => "col-xs-5"]])->widget(\kartik\select2\Select2::classname(), [
                                    'data' => $dayList,
                                    'language' => 'de',
                                    'options' => ['placeholder' => '请选择', 'multiple' => 'multiple'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label(false) ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                单独屏蔽日期<br>
                                如需在正常门诊日屏蔽预约请设置此日期

                                <?php
                                $sdate = strtotime(date('Y-m-01'));
                                $edate = strtotime('+6 month', strtotime(date('Y-m-01')));
                                $days = ($edate - $sdate) / 86400;
                                for ($i = 0; $i < $days; $i++) {
                                    $time = strtotime('+' . $i . ' day', $sdate);
                                    $dayList[date('Y-m-d', $time)] = date('Y-m-d', $time);
                                }
                                ?>
                            </th>
                            <td><?= $form->field($model, 'non_date', ['options' => ['class' => "col-xs-5"]])->widget(\kartik\select2\Select2::classname(), [
                                    'data' => $dayList,
                                    'language' => 'de',
                                    'options' => ['placeholder' => '请选择', 'multiple' => 'multiple'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label(false) ?>
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
                            <td><?= $form->field($model, 'phone', ['options' => ['class' => "col-xs-5"]])->textInput()->label(false) ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                门诊描述（选择预约项目温馨提醒）<br>
                                门诊日期，预约注意事项等
                            </th>
                            <td><?= $form->field($model, 'info')->textarea(['rows' => 5])->label(false) ?>
                            </td>
                        </tr>
                        <?php if ($type == 1) { ?>
                            <tr>
                                <td><?= $form->field($model, 'is_month')->checkbox([], false) ?>
                                    <br>
                                    注1：限制月龄开启后家长预约儿童体检需要选择体检月龄，系统会根据下方"体检限制月龄设置"<br>
                                    和儿童生日判断是否可以预约<br>
                                    注2：判断宝宝是否可约是按照宝宝出生日期和预约日期判断
                                </td>
                                <td></td>

                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php if ($type == 1) { ?>
                        <div>
                            <!-- Nav tabs -->
                            <ul id="intervalTab" class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#">体检限制月龄设置</a></li>
                            </ul>
                        </div>

                        <div class="box-body table-responsive no-padding">
                            <div class="dataTables_wrapper form-inline dt-bootstrap">
                                <table class="table table-striped table-bordered detail-view col-md-12">
                                    <tbody>
                                    <?php
                                    foreach (\common\models\HospitalAppointMonth::$typeText as $k => $v) {
                                        ?>
                                        <tr>
                                            <td><?= $v ?></td>
                                            <td><?= Html::checkboxList('month[' . $k . ']', $hospitalAppointMonth, \common\models\HospitalAppointMonth::$monthText[$k]) ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>注：如选择2月，则宝宝出生日至今必须满2个自然月且小于3个自然月之间才可预约，
                                            如选择2月，3月则宝宝出生至今必须满2个自然月且小于4个自然月才可预约

                                        </td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    if ($type == 4) {
                        $data = \common\models\Vaccine::find()->select('name')->where(['adult' => 1])->indexBy('id')->column();
                    } elseif ($type == 2) {
                        $data = \common\models\Vaccine::find()->select('GROUP_CONCAT(`name` ,`type`) as a,id')->where(['adult' => 0])->indexBy('id')->groupBy('id')->column();
                        $data = [0 => '全部一类疫苗', -1 => '全部二类疫苗'] + $data;
                    }
                    ?>
                    <div>
                        <!-- Nav tabs -->
                        <ul id="intervalTab" class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#">号源设置</a></li>
                        </ul>
                    </div>
                    <div>
                        <!-- Nav tabs -->
                        <ul id="intervalTab" class="nav nav-tabs" role="tablist">
                            <li role="presentation" <?php if ($model->interval == 1) {
                                echo 'class="active"';
                            } ?>><a href="#tabyi" aria-controls="tabyi" role="tab"
                                    data-toggle="tab">一小时</a></li>
                            <li role="presentation" <?php if ($model->interval == 2) {
                                echo 'class="active"';
                            } ?>><a href="#tabban" aria-controls="tabban" role="tab"
                                    data-toggle="tab">半小时</a></li>
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
                                $weeks = [1, 2, 3, 4, 5, 6, 0];

                                foreach (\common\models\HospitalAppointWeek::$typeText as $k => $v) {
                                    if (\Yii::$app->user->identity->hospital != 110591 && in_array($k, [19, 20])) {
                                        continue;
                                    }
                                    if ($k < 7) {
                                        ?>
                                        <tr class="yi">
                                            <td nowrap="nowrap"><?= $v ?></td>
                                            <td><?= Html::textInput('num[1][' . $k . ']', $nums[1][$k] ? $nums[1][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[2][' . $k . ']', $nums[2][$k] ? $nums[2][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[3][' . $k . ']', $nums[3][$k] ? $nums[3][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[4][' . $k . ']', $nums[4][$k] ? $nums[4][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[5][' . $k . ']', $nums[5][$k] ? $nums[5][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[6][' . $k . ']', $nums[6][$k] ? $nums[6][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[0][' . $k . ']', $nums[0][$k] ? $nums[0][$k] : 0, ['style' => 'text-align:center;']) ?></td>

                                        </tr>
                                    <?php }
                                } ?>
                                <?php
                                foreach (\common\models\HospitalAppointWeek::$typeText as $k => $v) {
                                    if ($k > 6) {
                                        ?>
                                        <tr class="ban">
                                            <td nowrap="nowrap"><?= $v ?></td>
                                            <td><?= Html::textInput('num[1][' . $k . ']', $nums[1][$k] ? $nums[1][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[2][' . $k . ']', $nums[2][$k] ? $nums[2][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[3][' . $k . ']', $nums[3][$k] ? $nums[3][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[4][' . $k . ']', $nums[4][$k] ? $nums[4][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[5][' . $k . ']', $nums[5][$k] ? $nums[5][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[6][' . $k . ']', $nums[6][$k] ? $nums[6][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                            <td><?= Html::textInput('num[0][' . $k . ']', $nums[0][$k] ? $nums[0][$k] : 0, ['style' => 'text-align:center;']) ?></td>
                                        </tr>
                                    <?php }
                                } ?>
                                <?php

                                if (in_array($type, [2, 4])) {

                                    ?>

                                    <tr>
                                        <td>设置疫苗</td>
                                        <?php foreach ($weeks as $wk => $wv) { ?>

                                            <td>
                                                <?php
                                                $vaccines1 = \common\models\HospitalAppointVaccine::find()->select('vaccine')->where(['haid' => $model->id, 'type' => 1])->andWhere(['week' => $wv])->column();
                                                $vaccines2 = \common\models\HospitalAppointVaccine::find()->select('vaccine')->where(['haid' => $model->id, 'type' => 2])->andWhere(['week' => $wv])->column();

                                                echo Html::a('展开', '#', [
                                                    'data-target' => '#modal' . $wv,//关联模拟框(模拟框的ID)
                                                    'data-toggle' => "modal", //定义为模拟框 触发按钮
                                                    'data-id' => $wv,
                                                ]);
                                                Modal::begin([
                                                    'class' => 'modal',
                                                    'id' => 'modal' . $wv,
                                                    'header' => '<h5>设置疫苗</h5>',
                                                ]);
                                                if ($type == 2 || $type == 4) {
                                                    ?>
                                                    <table class="table table-striped table-bordered detail-view">
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="3" style="font-weight: bold;">选择上午/全天疫苗:</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                <?= \kartik\select2\Select2::widget([
                                                                    'name' => 'vaccine[' . $wv . ']',
                                                                    'data' => $data,
                                                                    'language' => 'de',
                                                                    'options' => ['placeholder' => '请选择', 'multiple' => 'multiple'],
                                                                    'showToggleAll' => false,
                                                                    'value' => $vaccines1,
                                                                    'pluginOptions' => [
                                                                        'allowClear' => true
                                                                    ],
                                                                ]) ?>
                                                            </td>
                                                        </tr>
                                                        <?php if ($type == 2 or $type == 4) { ?>
                                                            <tr>
                                                                <td colspan="3" style="font-weight: bold;">选择下午疫苗:</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <?= \kartik\select2\Select2::widget([
                                                                        'name' => 'vaccine1[' . $wv . ']',
                                                                        'data' => $data,
                                                                        'language' => 'de',
                                                                        'options' => ['placeholder' => '请选择', 'multiple' => 'multiple'],
                                                                        'showToggleAll' => false,
                                                                        'value' => $vaccines2,
                                                                        'pluginOptions' => [
                                                                            'allowClear' => true
                                                                        ],
                                                                    ]) ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <div class="form-group">
                                                                        注：只设置上午/全天疫苗，则按照全天可约判断，如需下午不可以设置无号即可<br>
                                                                        注：选择街道目前对一类疫苗有效，二类疫苗不受限制
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <tr>
                                                            <td colspan="3" style="font-weight: bold;">
                                                                疫苗预约上限设置(如设置10，则单日最多可以预约10个此疫苗，空表示不限制此疫苗，0表示该疫苗无号）:
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $hospitalV = \common\models\HospitalAppointVaccine::find()
                                                            ->select('vaccine')
                                                            ->where(['haid' => $model->id, 'week' => $wv])->groupBy('vaccine')->column();
                                                        if ($hospitalV) {

                                                            if (in_array(0, $hospitalV) && in_array(-1, $hospitalV)) {
                                                                $vQuery = Vaccine::find()->select('id,name,type');
                                                            } else {
                                                                $vQuery = Vaccine::find()->select('id,name,type')->andWhere(['in', 'id', $hospitalV]);
                                                                if (in_array(-1, $hospitalV)) {
                                                                    //查询所有二类疫苗
                                                                    $Va = Vaccine::find()->select('id,name,type')->andWhere(['type' => 1]);
                                                                }
                                                                if (in_array(0, $hospitalV)) {
                                                                    //查询所有一类类疫苗
                                                                    $Va = Vaccine::find()->select('id,name,type')->andWhere(['type' => 0]);
                                                                }
                                                                if ($Va) {
                                                                    $vQuery->union($Va);
                                                                }
                                                            }

                                                            $vaccines = $vQuery->all();

                                                        } else {
                                                            $vaccines = [];
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td>数量</td>
                                                            <td>疫苗名称</td>
                                                            <td>分配疫苗号源</td>
                                                        </tr>
                                                        <?php


                                                        foreach ($vaccines as $hak => $hav) {
                                                            $havn = \common\models\HospitalAppointVaccineNum::findOne(['haid' => $model->id, 'week' => $wv, 'vaccine' => $hav->id]);
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                           name="vaccine_num[<?= $wv ?>][<?= $hav->id ?>]"
                                                                           value="<?= is_numeric($havn->num) ? $havn->num : '' ?>"
                                                                           style="width: 50px;">
                                                                </td>
                                                                <td>
                                                                    <?= $hav->name . $havn->type ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo Html::a('分配', '#', [
                                                                        'class' => 'fenpei',
                                                                        'data-target' => '#vaccine-num',//关联模拟框(模拟框的ID)
                                                                        'data-toggle' => "modal", //定义为模拟框 触发按钮
                                                                        'data-week' => $wv,
                                                                        'data-vaccine' => $hav->id,
                                                                    ]);
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>


                                                        <tr>
                                                            <td colspan="3">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">关闭
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-red">关闭后点击"提交"保存设置</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                <?php } ?>
                                                <?php
                                                Modal::end();
                                                ?>


                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                <?php if(in_array($type, [13])){
                                    
                                    
                                    ?>
                                    
                                    <tr>
                                        <td>设置专家</td>
                                        <?php 
                                        $edata = \common\models\AppointExpert::find()->select('name')->where(['doctorid' => Yii::$app->user->identity->doctorid])->indexBy('id')->column();

                                        foreach ($weeks as $wk => $wv) { 
                                            $expert = \common\models\HospitalAppointExpert::find()->select('expert')->where(['haid' => $model->id])->andWhere(['week' => $wv])->column();

                                            ?>
                                        
                                            <td>
                                                <?php
                                            
                                                echo Html::a('展开', '#', [
                                                    'data-target' => '#modal' . $wv,//关联模拟框(模拟框的ID)
                                                    'data-toggle' => "modal", //定义为模拟框 触发按钮
                                                    'data-id' => $wv,
                                                ]);
                                                Modal::begin([
                                                    'class' => 'modal',
                                                    'id' => 'modal' . $wv,
                                                    'header' => '<h5>设置专家</h5>',
                                                ]);
                                                    ?>
                                                    <table class="table table-striped table-bordered detail-view">
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="3" style="font-weight: bold;">选择专家:</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                <?= \kartik\select2\Select2::widget([
                                                                    'name' => 'expert[' . $wv . ']',
                                                                    'data' => $edata,
                                                                    'language' => 'de',
                                                                    'options' => ['placeholder' => '请选择', 'multiple' => 'multiple'],
                                                                    'showToggleAll' => false,
                                                                    'value' => $expert,
                                                                    'pluginOptions' => [
                                                                        'allowClear' => true
                                                                    ],
                                                                ]) ?>
                                                            </td>
                                                        </tr>
                                                        
                                                        <?php
                                                        $hospitalV = \common\models\HospitalAppointExpert::find()
                                                            ->select('expert')
                                                            ->where(['haid' => $model->id, 'week' => $wv])->groupBy('expert')->column();
                                                        if ($hospitalV) {
                                                            $vQuery = \common\models\AppointExpert::find()->select('id,name')->andWhere(['in', 'id', $hospitalV]);
                                                            $experts = $vQuery->all();
                                                        } else {
                                                            $experts = [];
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td>数量</td>
                                                            <td>专家</td>
                                                            <td></td>
                                                        </tr>
                                                        <?php


                                                        foreach ($experts as $hak => $hav) {
                                                            $havn = \common\models\HospitalAppointExpertNum::findOne(['haid' => $model->id, 'week' => $wv, 'expert' => $hav->id]);
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                           name="expert_num[<?= $wv ?>][<?= $hav->id ?>]"
                                                                           value="<?= is_numeric($havn->num) ? $havn->num : '' ?>"
                                                                           style="width: 50px;">
                                                                </td>
                                                                <td>
                                                                    <?= $hav->name ?>
                                                                </td>
                                                                <td>
                                                                    
                                                                </td>
                                                            </tr>
                                                        <?php } ?>


                                                        <tr>
                                                            <td colspan="3">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">关闭
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-red">关闭后点击"提交"保存设置</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                <?php
                                                Modal::end();
                                                ?>


                                            </td>
                                        <?php } ?>
                                    </tr>
                                    
                                
                                
                                
                                <?php } ?>



                                <tr>
                                    <td>选择街道</td>
                                    <?php
                                    $street = \common\models\Street::find()->where(['doctorid' => Yii::$app->user->identity->doctorid])->select('title')->indexBy('id')->column();
                                    foreach ($weeks as $wk => $wv) { ?>

                                        <td><?= \kartik\select2\Select2::widget([
                                                'name' => 'streets[' . $wv . ']',
                                                'data' => $street,
                                                'language' => 'de',
                                                'options' => ['placeholder' => '请选择', 'multiple' => 'multiple'],
                                                'showToggleAll' => false,
                                                'value' => \common\models\HospitalAppointStreet::find()->select('street')->where(['haid' => $model->id])->andWhere(['week' => $wv])->column(),
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ]) ?></td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="7">(如社区不需要按照街道预约则不需要设置为空即可)</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
Modal::begin([
    'id' => 'vaccine-num',
    'header' => '<h5>分配疫苗</h5>',
]);
?>
    <form name="vaccine-num-form" id="vaccine_num_form" action="vaccine-save" method="post">
        <input name="_csrf-frontend"

               type="hidden"

               id="_csrf-frontend"

               value="<?= Yii::$app->request->csrfToken ?>">
        <input type="hidden" value="0" name="week">
        <input type="hidden" value="0" name="vaccine">
        <input type="hidden" value="<?= $type ?>" name="type">
        <table class="table table-striped table-bordered detail-view">
            <tbody>
            <tr>
                <td>
                    时间段
                </td>
                <td>
                    可预约数量
                </td>
            </tr>
            <?php foreach (\common\models\HospitalAppointWeek::$typeText as $k => $v) {
                if ($model->interval == 1 && $k > 6) {
                    break;
                }
                if ($model->interval == 2 && $k < 7) {
                    continue;
                }
                ?>
                <tr>
                    <td><?= $v ?></td>
                    <td><input type="text" class="form-control vaccine_num" name="vaccine_num[<?= $k ?>]" value=""
                               style="width: 50px;"></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2">
                    <button class="btn btn-default" type="submit">保存</button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
<?php
Modal::end();
?>


<?php
Modal::begin([
    'id' => 'extend',
    'header' => '<h5>扩展设置疫苗预约周期</h5>',
]);
?>
<?php echo Html::a('添加', '#', [
    'class' => 'extend-add btn btn-primary',
    'data-target' => '#extend-add',//关联模拟框(模拟框的ID)
    'data-toggle' => "modal", //定义为模拟框 触发按钮
]);
?>
    <table class="table table-striped table-bordered detail-view" id="vaccine_day_list">
    </table>
<?php
Modal::end();
?>
<?php
Modal::begin([
    'id' => 'extend-add',
    'options' => [
        'data-backdrop' => 'static',
        'tabindex' => false,
    ],
    'header' => '<h5>添加疫苗周期</h5>',
]);
?>
    <form name="vaccine-day-form" id="vaccine_day_form" action="vaccine-day-save" method="post">
        <input name="_csrf-frontend"

               type="hidden"

               id="_csrf-frontend"

               value="<?= Yii::$app->request->csrfToken ?>">
        <input type="hidden" value="<?= $type ?>" name="type">
        <table class="table table-striped table-bordered detail-view">
            <tbody>
            <tr>
                <td>
                    疫苗
                </td>
                <td>
                    <?= \kartik\select2\Select2::widget([
                        'name' => 'vaccine',
                        'data' => $data,
                        'options' => ['placeholder' => '请选择'],
                        'value' => '',

                    ]) ?>
                </td>
            </tr>
            <tr>
                <td>
                    预约周期（天）
                </td>
                <td><input type="text" class="form-control vaccine_num" name="day" value=""
                           style="width: 50px;"></td>
            </tr>
            <tr>

            </tr>
            <tr>
                <td colspan="3">
                    <button class="btn btn-default" type="submit">保存</button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
<?php
Modal::end();
?>

<?php
$interval = isset($model->interval) ? $model->interval : 1;
$is_month = isset($model->is_month) ? $model->is_month : 0;

$updateJs = <<<JS

var _week=0;
var is_add=0;
$('#vaccine-num').on('show.bs.modal', function (event) {

  var button = $(event.relatedTarget); // Button that triggered the modal
  var vaccine = button.data('vaccine'); // Extract info from data-* attributes
  var week=button.data('week');
  _week=week;
  $('#modal'+week).modal('hide');
  var modal = $(this);
  modal.find('.modal-body input[name="vaccine"]').val(vaccine);
  modal.find('.modal-body input[name="week"]').val(week);
  $('#vaccine_num_form').find(".vaccine_num").each(function(){
      $(this).val('');
  });
  $.get('/hospital-appoint/vaccine-num?vaccine='+vaccine+'&week='+week+'&type='+{$type},function(e){
        var list=e.list
        $.each(list, function(key, value){
            $('#vaccine_num_form input[name="vaccine_num['+value.appoint_time+']"]').val(value.num);
        });
    },'json');
})	
$('#vaccine-num').on('shown.bs.modal', function (event) {
  $("body").addClass("modal-open");
})
jQuery("#vaccine_num_form").submit(function(){
    let data = $("#vaccine_num_form").serialize();
    console.log(data);
    $.post('/hospital-appoint/vaccine-save',data,function(e){
        is_add=1;
        $('#extend-add').modal('hide');
        $('#modal'+_week).modal('show');
    },'json');
    return false;
});
jQuery("#vaccine_day_form").submit(function(){
    let data = $("#vaccine_day_form").serialize();
    console.log(data);
     $.post('/hospital-appoint/vaccine-day-save',data,function(e){
             $('#extend-add').modal('hide');
             extendlist();
     },'json');
    return false;
});
$('#extend').on('shown.bs.modal', function (event) {
    extendlist();
})
function extendlist(){
    var html = "<tbody><tr><td>疫苗</td><td>周期（天）</td><td>操作</td></tr>";
  $.get('/hospital-appoint/vaccine-day-list?type='+{$type},function(e){
      if(e.list){
        e.list.forEach(function(item,index,self){
           html = html+'<tr><td>'+item.vaccine+'</td><td>'+item.day+'</td><td><a href="/hospital-appoint/vaccine-day-del?id='+item.id+'">删除</a></td></tr>'
        })
        html = html+'<tbody>';
        console.log(html)
        jQuery('#vaccine_day_list').html(html);
        }
      },'json');
  
}

$('#vaccine-num').on('hidden.bs.modal', function (event) {
            $('#modal'+_week).modal('show');
    $("body").addClass("modal-open");

})
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