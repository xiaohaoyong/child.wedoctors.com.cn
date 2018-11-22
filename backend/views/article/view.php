<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->info->title;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    video{width: 600px;}
    image{width: 600px;}
</style>
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
                            'format'=>'raw',
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
                <div class="form-group" style="padding-top: 20px;">
                    <?= Html::a('通过', ['verify?t=1&id='.$model->id],
                        ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('不通过', ['verify?t=2&id='.$model->id],
                        ['class' => 'btn btn-danger']) ?>
                    <?= Html::a('编辑', ['update', 'id' => $model->id],
                        ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
