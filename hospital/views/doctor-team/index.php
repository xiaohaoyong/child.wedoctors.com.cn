<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\DoctorTeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'添加','url'=>['create']]
];
?>
<div class="doctor-team-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                是否自动匹配医生团队（如需自定义不需要开启）：
                <?= \dosamigos\switchinput\SwitchBox::widget([
                    'name' => 'is_team',
                    'checked' => $userDoctor['is_team'],
                    'clientOptions' => [
                        'size' => 'mini',
                        'onColor' => 'success',
                        'offColor' => 'danger'
                    ],
                    'clientEvents' => [
                        'switchChange.bootstrapSwitch' => "function(e){
                            ajax(e.currentTarget.checked);
                        }"
                    ],
                ]);?>


            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>
                                                <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
                                    </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                                                                            <?= GridView::widget([
                            'options'=>['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,
                            
     'columns' => [

            'title',
            'intro',
            [
                'attribute' => 'type',
                'value' => function ($e) {
                    $type=[0=>'全部',1=>'儿保团队',2=>'孕产妇团队'];
                    return $type[$e->type];
                }
            ],


                            [
                            'class' => 'common\components\grid\ActionColumn',
                            'template'=>'
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>{update}</li>
                                </ul>
                            </div>
                            ',
                            ],
                            ],
                            ]); ?>
                                                                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function ajax($checked) {

        if($checked){
            $is_team=1;
        }else{
            $is_team=0;
        }
        $.get('/doctor-team?is_team='+$is_team,function (e) {

        })
    }
</script>