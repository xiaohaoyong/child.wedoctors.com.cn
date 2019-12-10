<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserDoctorSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parents common\models\UserParent */

$this->title = '社区管理';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
    0=>['name'=>'添加','url'=>['create']]
];
databackend\assets\DatabasesAsset::register($this);

?>
<div class="col-xs-12">
    <div class="box">
        <div class="box-body no-padding">
            <table id="example2" class="table table-bordered table-hover"  style="font-size: 12px;">
                <thead>
                <tr>
                    <th>id</th>

                    <th>社区卫生服务中心</th>
                    <th>名称</th>

                    <th>辖区内管理儿童数</th>
                    <th>今日签约 </th>
                    <th>签约总数</th>
                    <th>已宣教数</th>
                    <th>数据</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($doctor as $k=>$v){
                    ?>
                    <tr>
                        <td><?=$v->hospitalid?>,<?=$v->userid?></td>

                        <td><?=$v->name?></td>
                        <td><?=$v->phone?></td>

                        <td><?=$total=\common\models\ChildInfo::find()
                                ->where(['source'=>$v->hospitalid])
                                ->andFilterWhere(['admin'=>$v->hospitalid])
                                ->andFilterWhere(['>','birthday',strtotime('-3 year')])->count()?></td>
                        <td><?php
                            $today=strtotime(date('Y-m-d 00:00:00'));
                            //今日已签约
                            echo  \common\models\ChildInfo::find()
                                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                                ->andFilterWhere(['`doctor_parent`.doctorid'=>$v->userid])
                                ->andFilterWhere(['`doctor_parent`.level'=>1])
                                ->andFilterWhere([">",'`doctor_parent`.createtime',$today])->count();
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
                            //已宣教
                            echo  \common\models\ArticleUser::find()
                                ->andFilterWhere(['userid'=>$v->userid])->count();
                            ?></td>
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
                        <td><span class="badge <?=$color?>"><?=$baifen?>%</span></td>
                        <td>
                            <?=Html::a('<span class="fa fa-database"></span> 编辑', \yii\helpers\Url::to(['user-doctor/update',"id"=>$v->userid]));?>
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
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
