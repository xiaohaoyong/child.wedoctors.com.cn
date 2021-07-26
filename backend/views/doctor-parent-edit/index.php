<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DoctorParentEditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'添加','url'=>['create']]
];
?>
<div class="doctor-parent-edit-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>
                                                <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
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
                            ['class' => 'yii\grid\SerialColumn'],

                                        'id',
            'doctorid',
            'userid',
            //'childid',
            'createtime:datetime',
            [
                'attribute' => 'img',
                'format' => 'raw',
                'value' => function($model) {
                    return (empty($model->img)) ? '' : Html::img($model->img, ['height' => '30px']);
                }
            ],
            // 'teamid',
         [
             'attribute' => 'level',
             'value' => function ($e) {
                 return \common\models\DoctorParentEdit::$levelText[$e->level];
             }
         ],

                            [
                            'class' => 'common\components\grid\ActionColumn',
                            'template'=>'
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>{update}</li>
                                    <li>{delete}</li>
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