<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DoctorParentEdit */

$this->title ='编辑';
$this->params['breadcrumbs'][] = ['label' => 'Doctor Parent Edits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="doctor-parent-edit-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
