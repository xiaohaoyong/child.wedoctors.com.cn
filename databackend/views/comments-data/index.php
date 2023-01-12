<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $appointcomment \common\models\AppointComment */
/* @var $form yii\widgets\ActiveForm */


$this->title = '评价数据统计';
$this->params['breadcrumbs'][] = $this->title;
\databackend\assets\TableAsset::register($this);
?>

<div class="appoint-index">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-header with-border">
                <h3 class="box-title">
                    各社区评价数据
                </h3>
            </div>
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">检索：</h3>

                                    <?php $form = ActiveForm::begin(); ?>

                                    <?= $form->field($appointcomment, 'hospitalid')->dropdownList(\common\models\UserDoctor::find()->select('name')->indexBy('userid')->andFilterWhere(['>', 'userid', '37'])->andFilterWhere(['county' => \Yii::$app->user->identity->county])->column(), ['prompt' => '请选择'])->lable('社区') ?>

                                    <?=\kartik\date\DatePicker::widget([
                                        'name' => 'sdate',
                                        'value'=>Yii::$app->request->post('sdate'),
                                        'pluginOptions' => [
                                            'format' => 'yyyy-mm-dd',
                                            'autocomplete'=>'off',
                                            'todayHighlight' => true
                                        ]
                                    ]);?>
                                    <?=\kartik\date\DatePicker::widget([
                                        'name' => 'edate',
                                        'value'=>Yii::$app->request->post('edate'),
                                        'pluginOptions' => [
                                            'format' => 'yyyy-mm-dd',
                                            'autoclose'=>true,
                                            'todayHighlight' => true
                                        ]
                                    ]);?>

                                    <div class="form-group">
                                        <?= Html::submitButton('提 交', ['class' => 'btn btn-primary']) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::button('下载查询结果', ['id'=>'export','class' => 'btn btn-primary']) ?>
                                    </div>

                                    <?php ActiveForm::end(); ?>
                                    <div>
                                    </div>
                                    <!-- /.box-tools -->
                                </div>
                                <div class="box-body no-padding">
                                    <table id="example2" class="table table-bordered table-hover"  style="font-size: 12px;">
                                        <thead>
                                        <tr>
                                            <th>社区</th>
                                            <th>预约就诊后评价总数</th>
                                            <th>预约就诊后好评率 %</th>
                                            <th>预约就诊后中评率 %</th>
                                            <th>预约就诊后差评率 %</th>
                                            <!--<th>医生问题回复率 %</th>-->
                                            <th>医生回复及时性满意率 %</th>
                                            <th>医生回复问题解决率 %</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sdate=Yii::$app->request->post('sdate');
                                            $edate=Yii::$app->request->post('edate');
                                            foreach($arr_datas as $k=>$v){
                                             ?>
                                            <tr>
                                                <td><?=$v['name']?></td>
                                                <td><?=$v['ap_total']?></td>
                                                <td><?=$v['gd_total']?></td>
                                                <td><?=$v['md_total']?></td>
                                                <td><?=$v['ld_total']?></td>
                                                <!--<td><?=$v['q_gr']?></td>-->
                                                <td><?=$v['qc_gd_c']?></td>
                                                <td><?=$v['qc_gs_c']?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$updateJs = <<<JS
jQuery("#export").click(function () {
        var table2excel = new Table2Excel({'defaultFileName':'各社区评价数据'});
        table2excel.export(jQuery('#example2'));
    });
     
JS;
$this->registerJs($updateJs);

?>


