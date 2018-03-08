<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\operate\Operate */

$this->title = '添加记录对象';
$this->params['breadcrumbs'][] = ['label' => 'Operates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operate-create">

    <?= $this->render('_form', [
        'all' => $all,
        'operate' => $operate,
    ]) ?>

</div>
