<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = '编辑医生' ;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
\common\helpers\HeaderActionHelper::$action=[
    0=>['name'=>'列表','url'=>['index']]
];
?>
<div class="users-update">

    <?= $this->render('_form', [
        'model' => $model,
        'userInfo' => $userInfo,
        'userLogin' => $userLogin,
    ]) ?>

</div>
