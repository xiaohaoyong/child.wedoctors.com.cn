<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="article-view">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'attribute' => '标题',
                            'value' => function($e)
                            {
                                return $e->info->title;
                            }
                        ],
                        [
                            'attribute' => 'subject_pid',
                            'value' => function($e)
                            {
                                return \common\models\ArticleCategory::findOne([$e->subject_pid])->name;
                            }
                        ],
                        [
                            'attribute' => 'subject',
                            'value' => function($e)
                            {
                                return \common\models\ArticleCategory::findOne([$e->subject])->name;
                            }
                        ],
                        [
                            'attribute' => '内容',
                            'format'=>'html',
                            'value' => function($e)
                            {
                                return $e->info->content;
                            }
                        ],

                        [
                            'attribute' => 'createtime',
                            'format' => ['date', 'php:Y-m-d']
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
