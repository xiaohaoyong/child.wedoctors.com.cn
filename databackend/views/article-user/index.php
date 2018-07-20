<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel databackend\models\article\ArticleUserSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '宣教记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-user-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
                <!-- /.box-tools -->
            </div>
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options'=>['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['attribute' => 'childid', 'value' => function ($e) {
                                    return \common\models\ChildInfo::findOne($e->childid)->name;
                                }

                                ], ['attribute' => 'touserid', 'value' => function ($e) {
                                    $UserParent = \common\models\UserParent::findOne($e->touserid);
                                    return $UserParent->father."/".$UserParent->mother;
                                }

                                ],['attribute' => 'userid', 'value' => function ($e) {
                                    return \databackend\models\user\UserDoctor::findOne([$e->userid])->name;
                                }

                                ], ['attribute' => 'artid', 'value' => function ($e) {
                                    return \common\models\ArticleInfo::findOne($e->artid)->title;
                                }

                                ], ['attribute' => 'level', 'value' => function ($e) {
                                    return \common\models\ArticleUser::$levelText[$e->level];
                                }

                                ], ['attribute' => 'child_type', 'value' => function ($e) {
                                    return \common\models\Article::$childText[$e->child_type];
                                }

                                ], ['attribute' => 'createtime', 'format' => ['date', 'php:Y-m-d H:i:s']],            // 'userid',
                                // 'level',
                                // 'child_type',

                            ]
                        ]); ?>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
