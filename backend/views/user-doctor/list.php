<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\ChildInfo;
/* @var $this yii\web\View */
/* @var $searchModel databackend\models\AppointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '各社区签字/签约数据';
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
                                        <div>
                                            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                                        </div>
                                        <!-- /.box-tools -->
                                    </div>
                                    <div class="box-body no-padding">
                                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,

                            'columns' => [
                                'userid',
                                'name',
                                [
                                    'attribute' => 'is_team',
                                    'value' => function ($e) {
                                        return $e->is_team==1?'开通自动分配团队':'未开通自动分配团队';
                                    }
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
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
        var table2excel = new Table2Excel({'defaultFileName':'{$sdate}至{$sdate}各社区签约情况'});
        table2excel.export(jQuery('#example2'));
    });
     
JS;
$this->registerJs($updateJs);
?>