<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '中医指导库';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options'=>['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,
                            'columns' => [
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
                                    'attribute' => 'child_type',
                                    'value' => function($e)
                                    {
                                        return \common\models\Article::$childText[$e->child_type];
                                    }
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
