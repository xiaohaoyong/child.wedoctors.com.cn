<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ChatRecordContent */

$this->title = 'Create Chat Record Content';
$this->params['breadcrumbs'][] = ['label' => 'Chat Record Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chat-record-content-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
