<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ChildInfo */

$this->title = 'Update Child Info: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Child Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="child-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
