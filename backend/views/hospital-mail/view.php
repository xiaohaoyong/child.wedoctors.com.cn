<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\HospitalMail */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Hospital Mails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="hospital-mail-view">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <p>
                    <?= Html::a('Update', ['update', 'id' => $model->id],
                    ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                    ],
                    ]) ?>
                </p>

                <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                            'id',
            'content',
            'touser',
            'createtime:datetime',
            'is_show',
                ],
                ]) ?>

            </div>
        </div>
    </div>
</div>