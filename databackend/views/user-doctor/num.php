<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\ChildInfo;
/* @var $this yii\web\View */
/* @var $searchModel databackend\models\AppointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '各社区数据报表';
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
                                                <th>扫码签约数</th>
                                                <th>签约签字数</th>
                                                <th>预约总数</th>
                                                <th>其他渠道预约数</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sdate=Yii::$app->request->post('sdate');
                                            $edate=Yii::$app->request->post('edate');
                                            foreach($doctor as $k=>$v){
                                                $query=\common\models\HospitalForm::find()
                                                    ->select('sum(sign1) as a,sum(sign2) as b,sum(appoint_num) as c,sum(other_appoint_num) as d,')
                                                    ->where(['doctorid'=>$v->userid]);
                                                if(Yii::$app->request->post('edate') && Yii::$app->request->post('sdate')){
                                                    $query->andWhere(['>=','date',strtotime($sdate)])
                                                        ->andWhere(['<=','date',strtotime($edate)]);
                                                }
                                                $nums=$query->groupBy('userid')->asArray()->all();
                                                var_dump($nums);exit;




                                                ?>
                                                <tr>
                                                    <td><?=$v->name?></td>
                                                    <td><?php
                                                        echo ChildInfo::find()
                                                            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
                                                            ->andFilterWhere(['`child_info`.`source`' => $v->hospitalid])
                                                            ->andFilterWhere(['`child_info`.admin'=>$v->hospitalid])
                                                            ->count();
                                                        ?></td>
                                                    <td>
                                                        <?php
                                                        $query=ChildInfo::find()
                                                            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                                                            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                                                            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $v->userid])
                                                            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')])
                                                            ->andFilterWhere(['`child_info`.admin'=>$v->hospitalid]);
                                                        if(Yii::$app->request->post('edate') && Yii::$app->request->post('sdate')){
                                                            $query->andWhere(['>=','doctor_parent.createtime',strtotime($sdate)])->andWhere(['<=','doctor_parent.createtime',strtotime($edate)]);
                                                        }
                                                        echo $query->count();
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        //管辖儿童数（0-6）
                                                        $adminsix=ChildInfo::find()
                                                            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
                                                            ->andFilterWhere(['`child_info`.admin'=>$v->hospitalid])
                                                            ->count();

                                                        $nadminsix=ChildInfo::find()
                                                            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                                                            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $v->userid])
                                                            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                                                            ->andWhere(['!=','`child_info`.`admin`' ,$v->hospitalid])
                                                            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])

                                                                ->count();
                                                       $num=$adminsix+$nadminsix;
                                                       echo $num;
                                                        ?>
                                                    </td>
                                                    <td>

                                                        <?php
                                                        $query=\common\models\Autograph::find()->select('userid')->where(['doctorid'=>$v->userid]);
                                                        if(Yii::$app->request->post('edate') && Yii::$app->request->post('sdate')){
                                                            $query->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
                                                        }
                                                        $auto=$query->column();
                                                        if($auto) {
                                                            echo  ChildInfo::find()
                                                                ->andFilterWhere(['in', '`child_info`.`userid`', array_unique($auto)])
                                                                ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
                                                                ->count();
                                                        }else{
                                                            echo 0;
                                                        }
                                                        ?>

                                                    </td>
                                                    <td><?php
                                                        echo \common\models\Pregnancy::find()
                                                            ->andWhere(['field49'=>0])->andWhere(['>','field16',strtotime('-11 month')])->andWhere(['doctorid'=>$v->hospitalid])->count();
                                                        ?></td>
                                                    <td><?php
                                                        $query= \common\models\Pregnancy::find()
                                                            ->andWhere(['pregnancy.field49'=>0])
                                                            ->andWhere(['>', 'pregnancy.familyid', 0])
                                                            ->andWhere(['>','pregnancy.field16',strtotime('-11 month')])
                                                            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
                                                            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $v->userid]);
                                                        if(Yii::$app->request->post('edate') && Yii::$app->request->post('sdate')){
                                                            $query->andWhere(['>=','doctor_parent.createtime',strtotime($sdate)])->andWhere(['<=','doctor_parent.createtime',strtotime($edate)]);
                                                        }
                                                        echo $query->count();
                                                        ?></td>
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
        var table2excel = new Table2Excel({'defaultFileName':'{$sdate}至{$sdate}各社区签约情况'});
        table2excel.export(jQuery('#example2'));
    });
     
JS;
$this->registerJs($updateJs);
?>