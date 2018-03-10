<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '宣教指导';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'添加','url'=>['create']]
];
?>
<div class="article-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'columns' => [

            'id',
            [
                'attribute' => '标题',
                'value' => function($e)
                {
                    return $e->info->title;
                }
            ],
            [
                'attribute' => 'catid',
                'value' => function($e)
                {
                    return \common\models\Article::$catText[$e->catid];
                }
            ],
            [
                'attribute' => 'child_type',
                'value' => function($e)
                {
                    return \common\models\Article::$childText[$e->child_type];
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
                </div>
                ',
            ],
        ],
    ]); ?>
</div>
