<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\ChildInfo;
/* @var $this yii\web\View */
/* @var $searchModel databackend\models\AppointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '社区每日统计报表';
$this->params['breadcrumbs'][] = $this->title;
\databackend\assets\TableAsset::register($this);
?>
    <div class="appoint-index">
        <div class="col-xs-12">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">检索：</h3>
                                        <?php $form = ActiveForm::begin(); ?>
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
                                            <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                                                'btn btn-primary']) ?>
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
                                                <th>社区卫生服务中心</th>
                                                <th>儿童签字数</th>
                                                <th>孕妇签字数</th>
                                                <th>门诊预约数</th>
                                                <th>其他预约渠道</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($doctors as $k=>$v){
                                                $userDoctor=\common\models\UserDoctor::findOne(['userid'=>$v['doctorid']]);
                                                ?>
                                                <tr>
                                                    <td><?=$userDoctor->name?></td>
                                                    <td><?=$v['b']?></td>
                                                    <td><?=$v['e']?></td>
                                                    <td><?=$v['c']?></td>
                                                    <td><?=$v['d']?></td>
                                                </tr>
                                            <?php }?>

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
jQuery(".krajee-datepicker").attr("autocomplete", "off");

jQuery("#export").click(function () {
        var table2excel = new Table2Excel({'defaultFileName':'{$sdate}至{$sdate}社区每日统计报表'});
        table2excel.export(jQuery('#example2'));
    });
     
JS;
$this->registerJs($updateJs);
?>