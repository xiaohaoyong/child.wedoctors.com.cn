<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\AppointCallingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="appoint-calling-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <div>
                    <h3 class="box-title">设置：</h3>
                    是否开启分诊台：<?= Html::checkbox('name', $userDoctor->is_zhenshi, ['id' => 'article_push']) ?>
                </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div id="w1" class="col-sm-12">
                            <div class="summary">第<b>1-4</b>条，共<b>4</b>条数据.</div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>#</th>
                                </thead>
                                <tbody>
                                <?php foreach (\common\models\HospitalAppoint::$typeText as $k => $v) { ?>
                                    <tr data-key="<?= $k ?>">
                                        <td><?= $v ?>排队列表</td>
                                        <td><a href="/appoint-calling/list?type=<?= $k ?>" title="更新" aria-label="更新"
                                               data-pjax="0"><span class="glyphicon glyphicon-pencil"></span> 跳转</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr data-key="<?= $k + 1 ?>">
                                    <td>我的诊室</td>
                                    <td><a href="/appoint-calling/room" title="更新" aria-label="更新" data-pjax="0"><span
                                                    class="glyphicon glyphicon-pencil"></span> 跳转</a></td>
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
<?php
$updateJs = '
    jQuery("#article_push").click(function () {
        if(this.checked==true){
            if(confirm("注：\n1，勾选后将由在叫号机取号用户将优先分配至分诊台\n2，分诊台确认后分配至诊室")){
               jQuery.get("/appoint-calling/ispush?id=1",{},function(e){
               });
            }else{
               this.checked=false;
            }
        }else{
            jQuery.get("/appoint-calling/ispush?id=0",{},function(e){
            });
        }
    });';
$this->registerJs($updateJs);
?>