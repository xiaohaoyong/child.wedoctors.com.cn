<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ChildInfoSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '健康档案';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="child-info-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'userid',
            [
                'attribute' => '账号电话',
                'value' => function($e)
                {
                    return \common\models\User::findOne($e->userid)->phone;
                }
            ],
            [
                'attribute'=>'儿童年龄',
                'value'=>function($e)
                {
                    return  date('Y')-date('Y',$e->birthday);
                }
            ],
            [
                'attribute'=>'父亲姓名',
                'value'=>function($e)
                {
                    return $e->parent->father;
                }
            ],
            [
                'attribute'=>'父亲电话',
                'value'=>function($e)
                {
                    return $e->parent->father_phone;

                }
            ],
            [
                'attribute'=>'母亲姓名',
                'value'=>function($e)
                {
                    return $e->parent->mother;

                }
            ],
            [
                'attribute'=>'母亲电话',
                'value'=>function($e)
                {
                    return $e->parent->mother_phone;

                }
            ],
            [
                'attribute'=>'签约时间',
                'value'=>function($e)
                {

                    return $e->sign->createtime?date('Y-m-d H:i',$e->sign->createtime):"未签约";
                }
            ],
            [
                'attribute'=>'签约医生',
                'value'=>function($e)
                {
                    return $e->doctor->name?$e->doctor->name:"未签约";
                }
            ],
            [
                'attribute'=>'医生社区',
                'value'=>function($e)
                {
                    return $e->doctor->hosptial->name?$e->doctor->hosptial->name:"未签约";
                }
            ],
            [
                'class' => 'common\components\grid\ActionColumn',
                'template'=>'
                <div class="btn-group dropup">
                    <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-settings"></i> 记录 <i class="fa fa-angle-up"></i></a>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>{articleuser} </li><li>{childhealthrecord}</li><li>{download}</li>
                    </ul>
                </div>',
                'buttons'=>[
                    'articleuser'=>function($url,$model,$key){
                        return Html::a('<span class="fa fa-database"></span> 宣教记录',\yii\helpers\Url::to(['article-user/index','ArticleUserSearchModel[childid]'=>$model->id]));
                    },
                    'childhealthrecord'=>function($url,$model,$key){
                        return Html::a('<span class="fa fa-database"></span> 健康档案',\yii\helpers\Url::to(['child-health-record/index','ChildHealthRecordSearchModel[childid]'=>$model->id]));
                    },
                    'download'=>function($url,$model,$key){
                        return Html::a('<span class="fa fa-database"></span> 下载宣教记录',\yii\helpers\Url::to(['article-user/download','childid'=>$model->id]));
                    }
                ],
            ],
        ],
    ]); ?>
</div>
