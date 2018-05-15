<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ChildHealthRecord */

$this->title ='添加';
$this->params['breadcrumbs'][] = ['label' => 'Child Health Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']]
];
?>
<div class="child-health-record-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
