<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DataUserTask */

$this->title ='添加';
$this->params['breadcrumbs'][] = ['label' => 'Data User Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']]
];
?>
<div class="data-user-task-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
