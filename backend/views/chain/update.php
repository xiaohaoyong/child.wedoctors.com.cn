<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Chain */

$this->title ='编辑';
$this->params['breadcrumbs'][] = ['label' => 'Chains', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="chain-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
