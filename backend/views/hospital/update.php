<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\hospital\Hospital */

$this->title = 'Update Hospital: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Hospitals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
\common\helpers\HeaderActionHelper::$action=[
    0=>['name'=>'列表','url'=>['index']],
    1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="hospital-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
