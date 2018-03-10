<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel databackend\models\article\ArticleUserSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '宣教记录';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [0 => ['name' => '返回健康档案', 'url' => ['child-info/index']]];
?>
<div class="article-user-index">

    <?= GridView::widget(['dataProvider' => $dataProvider,

        'columns' => [['attribute' => 'childid', 'value' => function ($e) {
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

        ], ['attribute' => 'createtime', 'format' => ['date', 'php:Y-m-d H:i:s']],            // 'userid',
            // 'level',
            // 'child_type',

        ],]); ?>
</div>
