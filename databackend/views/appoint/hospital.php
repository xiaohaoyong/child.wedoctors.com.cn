<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel databackend\models\AppointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '各社区预约数据';
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
                                            'autocomplete'=>'off',
                                            'todayHighlight' => true
                                        ]
                                    ]);?>

                                    <div class="form-group">
                                        <?= Html::submitButton($model->isNewRecord ? '提交'                    : '提交', ['class' => $model->isNewRecord ? 'btn btn-success' :
                                            'btn btn-primary']) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::button('下载当前结果', ['id'=>'export','class' => 'btn btn-primary']) ?>
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
                                <th>疫苗预约数</th>
                                <th>体检预约数 </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($hospital as $k=>$v){
                                $query1=\common\models\Appoint::find()->where(['doctorid'=>$v->userid])->andWhere(['type'=>2]);
                                $query2=\common\models\Appoint::find()->where(['doctorid'=>$v->userid])->andWhere(['type'=>1]);
                                if(Yii::$app->request->post('edate') && Yii::$app->request->post('sdate')){
                                    $sdate=Yii::$app->request->post('sdate');
                                    $edate=Yii::$app->request->post('edate');
                                    $query1->andWhere(['>=','appoint_date',strtotime($sdate)])->andWhere(['<=','appoint_date',strtotime($edate)]);
                                    $query2->andWhere(['>=','appoint_date',strtotime($sdate)])->andWhere(['<=','appoint_date',strtotime($edate)]);
                                }
                                ?>
                                <tr>
                                    <td><?=$v->name?></td>
                                    <td><?=$query1->count()?></td>
                                    <td><?=$query2->count()?></td>
                                </tr>
                            <?php }?>
                            <tr>
                                <?php
                                $query1t=\common\models\Appoint::find()->andWhere(['type'=>2]);
                                $query2t=\common\models\Appoint::find()->andWhere(['type'=>1]);
                                if(Yii::$app->request->post('edate') && Yii::$app->request->post('sdate')){
                                    $sdate=Yii::$app->request->post('sdate');
                                    $edate=Yii::$app->request->post('edate');
                                    $query1t->andWhere(['>=','appoint_date',strtotime($sdate)])->andWhere(['<=','appoint_date',strtotime($edate)]);
                                    $query2t->andWhere(['>=','appoint_date',strtotime($sdate)])->andWhere(['<=','appoint_date',strtotime($edate)]);
                                }?>
                                <td>总数</td>
                                <td><?=$query1t->count()?></td>
                                <td><?=$query2t->count()?></td>
                            </tr>
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
        var table2excel = new Table2Excel();
        table2excel.export(jQuery('#example2'));
    });
     
JS;
$this->registerJs($updateJs);
?>