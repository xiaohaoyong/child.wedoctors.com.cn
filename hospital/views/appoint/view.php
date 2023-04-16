<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Appoint */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Appoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '列表', 'url' => ['index']],
    1 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="appoint-view">
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
                        [
                            'attribute' => 'image',
                            'format'=>'raw',
                            'value' => function ($e) {
                                return Html::img($e->image,['class'=>'file-preview-image','style'=>'max-width:300px']);
                            }
                        ],
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>