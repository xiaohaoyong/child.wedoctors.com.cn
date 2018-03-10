<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\operate\Operate */

$this->title = $model->menu->description;
$this->params['breadcrumbs'][] = ['label' => 'Operates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operate-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
        ],
    ]) ?>

</div>
