<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Agreement */

$this->title ='编辑';
$this->params['breadcrumbs'][] = ['label' => 'Agreements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="agreement-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
