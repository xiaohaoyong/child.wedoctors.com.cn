<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\HospitalMailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '通知中心';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="hospital-mail-index">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="list-group">
                    <?php if($data){
                        foreach ($data as $k=>$v){?>
                    <li class="list-group-item">
                        <?=$v->content?>
                        <?php
                        $hospitalMailShow=\common\models\HospitalMailShow::find()
                            ->where(['mailid'=>$v->id])
                            ->andWhere(['hospitalid'=>\Yii::$app->user->identity->hospitalid])
                            ->one();
                        if(!$hospitalMailShow){
                            $hospitalMailShow=new \common\models\HospitalMailShow();
                            $hospitalMailShow->mailid=$v->id;
                            $hospitalMailShow->hospitalid=\Yii::$app->user->identity->hospitalid;
                            $hospitalMailShow->save();
                            echo "<span class=\"badge bg-yellow\">!</span>";
                        }
                        ?>
                        <p style="margin: 0;"><?=date('Y/m/d H:i',$v->createtime)?></p>
                    </li>
                    <?php }}else{?>
                    <li class="list-group-item">
                        暂无通知
                    </li>
                    <?php }?>
                    <?php
                    Yii::$app->db->createCommand()->batchInsert('hospital_mail_show',['mailid','hospitalid','createtime'],$rs);
                    ?>
                </ul>
                <?= \yii\widgets\LinkPager::widget(['pagination' => $page]); ?>
            </div>

        </div>
    </div>
</div>