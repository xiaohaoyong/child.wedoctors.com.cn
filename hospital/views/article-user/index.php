<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\article\ArticleUserSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '宣教记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-user-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options'=>['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['attribute' => 'childid', 'value' => function ($e) {
                                    if(!$name=\common\models\ChildInfo::findOne($e->childid)->name){
                                        $name=\common\models\ChildInfo::findOne(['userid'=>$e->touserid])->name;
                                    }
                                    return $name;
                                }

                                ], ['attribute' => 'touserid', 'value' => function ($e) {
                                    $UserParent = \common\models\UserParent::findOne($e->touserid);
                                    return $UserParent->father."/".$UserParent->mother;
                                }

                                ],['attribute' => 'userid', 'value' => function ($e) {
                                    return \hospital\models\user\UserDoctor::findOne([$e->userid])->name;
                                }

                                ], ['attribute' => 'artid', 'value' => function ($e) {
                                    return \common\models\ArticleInfo::findOne($e->artid)->title;
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
