<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\operate\OperateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '操作日志';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operate-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <hr>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'label' => '操作账号',
                'attribute' => 'name',
                'value' => function ($model){
                    return $model->admin->userlogin;
                }
            ],
            [
                'attribute' => 'operate_time',
                'format' => 'raw',
                'value' => function($model){
                    return date('Y-m-d H:i',$model->operate_time);
                }
            ],
            [
                'label' => '操作行为',
                'attribute' => 'operate',
                'value' => function ($model){
                    return $model->menu->description;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{delete}',
            ],
        ],
    ]); ?>
</div>
