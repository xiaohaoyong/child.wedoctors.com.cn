<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel databackend\models\message\MessageSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'添加','url'=>['create']]
];
?>
<div class="message-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <hr>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
'columns' => [
            'id',
    [
        'attribute' => 'type',
        'value' => function($e)
        {
            return \common\models\Article::$childText[$e->type];
        }
    ],
            'title',
    'content',

    [
        'attribute' => 'createtime',
        'format' => ['date', 'php:Y-m-d']
    ],

        ],
    ]); ?>
</div>
