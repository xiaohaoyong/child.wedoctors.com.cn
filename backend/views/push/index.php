<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ChildInfoSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '开通自动推送';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-xs-12">
    <div class="box">
        <div class="box-body no-padding">
            <table id="example2" class="table table-bordered table-hover"  style="font-size: 12px;">
                <thead>
                <tr>
                    <th>社区卫生服务中心</th>
                    <th>是否开通</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $redis=\Yii::$app->rdmp;
                $hospital=$redis->hgetall('article_send_ispush');

                foreach($hospital as $k=>$v){
                    if($k%2==0){
                        $key=$v;
                    }else {
                        $doctor = \common\models\UserDoctor::findOne(['hospitalid' => $key]);
                    ?>
                    <tr>
                        <td><?=$doctor->name?></td>
                        <td><?=$v?"Y":"N"?></td>
                    </tr>
                <?php }}?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
</div>