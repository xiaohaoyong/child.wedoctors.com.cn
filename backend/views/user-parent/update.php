<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserParent */

$this->title ='编辑';
$this->params['breadcrumbs'][] = ['label' => 'User Parents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->userid, 'url' => ['view', 'id' => $model->userid]];
$this->params['breadcrumbs'][] = 'Update';

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="user-parent-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
