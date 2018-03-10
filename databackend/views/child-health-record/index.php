<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel databackend\models\ChildHealthRecordSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '儿童健康档案';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [0 => ['name' => '返回健康档案', 'url' => ['child-info/index']]];
?>
<div class="child-health-record-index">

    <?= GridView::widget(['dataProvider' => $dataProvider,

        'columns' => [['attribute' => 'childid', 'value' => function ($e) {
            return \common\models\ChildInfo::findOne($e->childid)->name;
        }

        ], ['attribute' => 'userid', 'value' => function ($e) {
            return \databackend\models\user\UserDoctor::findOne([$e->userid])->name;
        }

        ], 'content', ['attribute' => 'createtime', 'format' => ['date', 'php:Y-m-d H:i:s']]


        ],]); ?>
</div>
