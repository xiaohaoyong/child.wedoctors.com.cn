<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Points */

$this->title ='添加';
$this->params['breadcrumbs'][] = ['label' => 'Points', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']]
];
?>
<div class="points-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
