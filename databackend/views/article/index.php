<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '宣教指导';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options'=>['class' => 'col-sm-12'],
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
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
