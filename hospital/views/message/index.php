<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\message\MessageSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '历史消息列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'添加','url'=>['create']]
];
?>
<div class="message-index">


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
