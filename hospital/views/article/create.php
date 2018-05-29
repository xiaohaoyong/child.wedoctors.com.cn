<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title ='添加';
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']]
];
?>
<div class="article-create">
    <?= $this->render('_form', [
        'article'=>$article,
        'model' => $model,
    ]) ?>
</div>
