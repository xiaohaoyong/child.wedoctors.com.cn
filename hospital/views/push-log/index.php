<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ChildInfoSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '未完成签约用户统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="child-info-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <h3 class="box-title">每日召回数据统计：</h3>
                <table class="table table-bordered table-hover" >
                    <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>召回成功数</th>
                    </tr>
                    <tr>
                        <th>扫码未授权人数：</th>
                        <th><?=$dataProvider->data[1]?></th>
                        <th>自动发送召回数：</th>
                        <th><?=$dataProvider->data[2]?$dataProvider->data[2]:0?></th>
                        <th>发送成功数（取消关注公众号则发送不成功）：</th>
                        <th><?=$dataProvider->data[3]?$dataProvider->data[3]:0?></th>
                        <?php
                        $ok=$dataProvider->data[2]-$dataProvider->data[1];
                        ?>
                        <th><?=$ok<0?0:$ok?></th>
                    </tr>
                    <tr>
                        <th>授权未添加宝宝：</th>
                        <th><?=$dataProvider->data[4]?></th>
                        <th>自动发送召回数：</th>
                        <th><?=$dataProvider->data[5]?$dataProvider->data[5]:0?></th>
                        <th>发送成功数（取消关注公众号则发送不成功）：</th>
                        <th><?=$dataProvider->data[6]?$dataProvider->data[6]:0?></th>
                        <?php
                        $ok=$dataProvider->data[5]-$dataProvider->data[4];
                        ?>
                        <th><?=$ok<0?0:$ok?></th>
                    </tr>
                    </thead>
                </table>
                注：自动召回每日晚8点执行。
                <h3 class="box-title">授权未添加宝宝用户列表：</h3>
                <table id="example2" class="table table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th>用户ID</th>
                            <th>用户手机号</th>
                            <th>注册时间</th>
                            <th>授权时间</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($dataProvider->noChild as $k=>$v){
                        $user=\common\models\User::findOne($v->parentid);
                    ?>
                    <tr>
                        <td><?=$v->parentid?></td>
                        <td><?=$user->phone?></td>
                        <td><?=date('Y-m-d H:i',$user->createtime)?></td>
                        <td><?=date('Y-m-d H:i',$v->createtime)?></td>
                    </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
