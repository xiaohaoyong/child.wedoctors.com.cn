<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserDoctorAppoint */

$this->title ='预约系统设置';
$this->params['breadcrumbs'][] = ['label' => 'User Doctor Appoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doctorid, 'url' => ['view', 'id' => $model->doctorid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-doctor-appoint-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
