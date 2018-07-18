<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WeOpenid */

$this->title ='编辑';
$this->params['breadcrumbs'][] = ['label' => 'We Openids', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="we-openid-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
