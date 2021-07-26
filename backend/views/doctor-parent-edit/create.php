<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DoctorParentEdit */

$this->title ='添加';
$this->params['breadcrumbs'][] = ['label' => 'Doctor Parent Edits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']]
];
?>
<div class="doctor-parent-edit-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
