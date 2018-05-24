<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '通知';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'添加指导','url'=>['zhongyi']]
];
?>
<div class="article-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'columns' => [

            'id',
            [
                'attribute' => '通知标题',
                'value' => function($e)
                {
                    return $e->info->title;
                }
            ],
            [
                'attribute' => 'createtime',
                'format' => ['date', 'php:Y-m-d']
            ],
            // 'num',
            // 'type',

            [
                'class' => 'common\components\grid\ActionColumn',
                'template'=>'
                <div class="btn-group dropup">
                    <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>{update} </li><li>{delete}</li>
                    </ul>
                </div>',
                'buttons'=>[
                    'update'=>function($url,$model,$key){
                        return Html::a('<span class="fa fa-database"></span> 修改',\yii\helpers\Url::to(['article/zhongyi','id'=>$model->id]));
                    },
                ],
            ],
        ],
    ]); ?>
</div>
