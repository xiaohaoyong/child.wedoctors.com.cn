<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BabyGuide */

$this->title ='编辑';
$this->params['breadcrumbs'][] = ['label' => 'Baby Guides', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="baby-guide-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
