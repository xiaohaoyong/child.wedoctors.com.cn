<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DataUserSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'添加','url'=>['create']]
];
?>
<div class="data-user-index">

    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
    <hr>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
    'columns' => [
            'id',
            'username',
            [
                'attribute' => 'type',
                'value' => function($data)
                {
                    return \common\models\DataUser::$typeText[$data->type];
                }
            ],
            [
                'attribute' => 'province',
                'value' => function($e)
                {
                    return \common\models\Area::$all[$e->province];
                }
            ],
            [
                'attribute' => 'city',
                'value' => function($e)
                {
                    return \common\models\Area::$all[$e->city];
                }
            ],
            [
                'attribute' => 'county',
                'value' => function($e)
                {
                    return \common\models\Area::$all[$e->county];
                }
            ],
            [
                'attribute' => 'hospital',
                'value' => function($data)
                {
                    $hospital=\common\models\Hospital::findOne($data->hospital);
                    return $hospital->name;
                }
            ],

            [
                'attribute' => 'createtime',
                'format' => ['date', 'php:Y-m-d']
            ],
            [
                'attribute' => 'lasttime',
                'value' => function($data)
                {
                    return $data->lasttime ? date('Y-m-d H:i',$data->lasttime):"未登录";
                }
            ],

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
