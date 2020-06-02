<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\ChildInfo;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserDoctorSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parents common\models\UserParent */

$this->title = '社区数据统计';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'添加','url'=>['create']]
];
hospital\assets\DatabasesAsset::register($this);

?>
<div class="user-doctor-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">

            <h3 class="box-title">
                0-3岁中医儿童健康管理数据统计
            </h3>
            </div>
            <div class="box-body no-padding">
                <table id="example2" class="table table-bordered table-hover"  style="font-size: 12px;">
                    <thead>
                    <tr>
                        <th>社区卫生服务中心</th>
                        <th>辖区内管理儿童数</th>
                        <th>今日签约 </th>
                        <th>签约总数</th>
                        <th>今日宣教</th>
                        <th>已宣教数</th>
                        <th>管理服务率</th>
                        <th>数据</th>
                        <th>宣教记录</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($doctor as $k=>$v){
                        ?>
                        <tr>
                            <td><?=$v->name?></td>
                            <td><?=$total=\common\models\ChildInfo::find()->andFilterWhere(['source'=>$v->hospitalid])->andFilterWhere(['admin'=>$v->hospitalid])->andFilterWhere(['>','birthday',strtotime('-3 year')])->count()?></td>
                            <td><?php
                                $today=strtotime(date('Y-m-d 00:00:00'));
                                //今日已签约
                                echo \common\models\ChildInfo::find()
                                    ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                                    ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                                    ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
                                    ->andFilterWhere(['child_info.admin'=>$v->hospitalid])
                                    ->andFilterWhere([">",'`doctor_parent`.createtime',$today])
                                    ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
                                    ->count()
                                ?>
                            </td>
                            <td><?php
                                //已签约总数
                                echo  $q=\common\models\ChildInfo::find()
                                    ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                                    ->andFilterWhere(['`doctor_parent`.doctorid'=>$v->userid])
                                    ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
                                    ->andFilterWhere(['child_info.admin'=>$v->hospitalid])
                                    ->andFilterWhere(['`doctor_parent`.level'=>1])->count();
                                ?>
                            </td>
                            <td><?php
                                $today=strtotime(date('Y-m-d 00:00:00'));
                                //今日宣教
                                echo  \common\models\ArticleUser::find()
                                    ->andFilterWhere(['userid'=>$v->userid])
                                    ->andFilterWhere([">",'createtime',$today])->count();
                                ?></td>
                            <td><?php
                                //已宣教
                                echo  \common\models\ArticleUser::find()
                                    ->andFilterWhere(['userid'=>$v->userid])->count();
                                ?></td>
                            <td>
                                <?php
                                if($total) {$baifen= round(($q/$total) * 100,1);}else{$baifen= 0;}

                                if($baifen>44){

                                    $color='bg-green';
                                    $color1='progress-bar-success';
                                }elseif($baifen>34){
                                    $color='bg-yellow';
                                    $color1='progress-bar-yellow';
                                }else{
                                    $color='bg-red';
                                    $color1='progress-bar-danger';
                                }

                                ?>
                                <div class="progress progress-xs">
                                    <div class="progress-bar <?=$color1?>" style="width: <?=$baifen?>%"></div>
                                </div>
                            </td>
                            <td><span class="badge <?=$color?>"><?=$baifen?>%</span></td>
                            <td><?=Html::a('<span class="fa fa-database"></span> 宣教记录', \yii\helpers\Url::to(['article-user/index',"ArticleUserSearchModel[userid]"=>$v->userid]));?></td>

                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<div class="appoint-index">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-header with-border">
            <h3 class="box-title">
                家医签约数据统计（0-6岁儿童，孕产妇，疫苗体检预约）
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
        $(function () {
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "iDisplayLength":100,
                "bPaginate":false,
                "bInfo":false
            });
        });
JS;
$this->registerJs($updateJs);

?>
