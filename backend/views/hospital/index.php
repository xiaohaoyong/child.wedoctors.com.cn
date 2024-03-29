<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\hospital\HospitalSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理数据';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [0 => ['name' => '添加', 'url' => ['create']]];
?>
<div class="hospital-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <hr>
    <?= GridView::widget(['dataProvider' => $dataProvider,

        'columns' => [['class' => 'yii\grid\SerialColumn'], 'name', ['attribute' => 'province', 'value' => function ($e) {
            return \common\models\Area::$all[$e->province];
        }], ['attribute' => 'city', 'value' => function ($e) {
            return \common\models\Area::$all[$e->city];
        }], ['attribute' => 'county', 'value' => function ($e) {
            return \common\models\Area::$all[$e->county];
        }], 'area', ['class' => 'common\components\grid\ActionColumn', 'template' => '
                <div class="btn-group dropup">
                    <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>{update} </li><li>{delete}</li>
                    </ul>
                </div>
                ',],


        ],]); ?>
</div>
