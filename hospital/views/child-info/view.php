<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model hospital\models\user\ChildInfo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Child Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="child-info-view">

    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                       
                        'name',
                        'birthday:datetime',
                        'idcard',

                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
