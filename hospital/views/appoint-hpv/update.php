<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AppointHpv */

$this->title ='编辑';
$this->params['breadcrumbs'][] = ['label' => 'Appoint Hpvs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="appoint-hpv-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
