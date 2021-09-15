<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Doctors */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Doctors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctors-view">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'name',
                        [
                            'attribute' => '手机号',
                            'value' => function ($e) {
                                $user=\common\models\User::findOne($e->userid);
                                return $user->phone;
                            }
                        ],
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>