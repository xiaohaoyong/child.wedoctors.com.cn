<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\UserParent */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'User Parents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="user-parent-view">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <p>
                    <?= Html::a('Update', ['update', 'id' => $model->userid],
                    ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $model->userid], [
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
                            'userid',
            'mother',
            'mother_phone',
            'mother_id',
            'father',
            'father_phone',
            'father_birthday',
            'state',
            'address',
            'source',
            'field34',
            'field33',
            'field30',
            'field29',
            'field28',
            'field12',
            'field11',
            'field1',
            'province',
            'county',
            'city',
            'fbirthday',
                ],
                ]) ?>

            </div>
        </div>
    </div>
</div>