<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '待审宣教指导';
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
                            'options' => ['class' => 'col-sm-12'],

                            'dataProvider' => $dataProvider,

                            'columns' => [

                                'id',
                                [
                                    'attribute' => '标题',
                                    'value' => function ($e) {
                                        return $e->info->title;
                                    }
                                ],
                                [
                                    'attribute' => 'subject_pid',
                                    'value' => function ($e) {
                                        return \common\models\ArticleCategory::findOne([$e->subject_pid])->name;
                                    }
                                ],
                                [
                                    'attribute' => 'subject',
                                    'value' => function ($e) {
                                        return \common\models\ArticleCategory::findOne([$e->subject])->name;
                                    }
                                ],
                                [
                                    'attribute' => 'child_type',
                                    'value' => function ($e) {
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
                                    'template' => '
                <div class="btn-group dropup">
                    <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>{view} </li>
                    </ul>
                </div>
                ',
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>