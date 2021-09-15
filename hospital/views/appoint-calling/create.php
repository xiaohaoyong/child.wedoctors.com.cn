<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AppointCalling */

$this->title ='添加';
$this->params['breadcrumbs'][] = ['label' => 'Appoint Callings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']]
];
?>
<div class="appoint-calling-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
