<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Appoint */

$this->title = 'Create Appoint';
$this->params['breadcrumbs'][] = ['label' => 'Appoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appoint-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
