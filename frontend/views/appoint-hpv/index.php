<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\AppointHpvSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Appoints';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appoint-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Appoint', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'userid',
            'doctorid',
            'createtime:datetime',
            'appoint_time:datetime',
            //'appoint_date',
            //'type',
            //'childid',
            //'phone',
            //'state',
            //'loginid',
            //'remark',
            //'cancel_type',
            //'push_state',
            //'mode',
            //'vaccine',
            //'month',
            //'orderid',
            //'street',
            //'source',
            //'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
