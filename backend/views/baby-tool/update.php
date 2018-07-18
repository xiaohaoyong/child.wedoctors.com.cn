<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BabyTool */

$this->title ='编辑';
$this->params['breadcrumbs'][] = ['label' => 'Baby Tools', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="baby-tool-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
