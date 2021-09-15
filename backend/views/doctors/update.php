<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Doctors */

$this->title ='编辑';
$this->params['breadcrumbs'][] = ['label' => 'Doctors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->userid]];
$this->params['breadcrumbs'][] = 'Update';

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="doctors-update">

    <?= $this->render('_form', [
        'docHospital'=>$docHospital,

        'model' => $model,
    ]) ?>

</div>
