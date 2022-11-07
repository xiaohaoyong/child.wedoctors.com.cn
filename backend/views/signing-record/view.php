<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SigningRecord */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Signing Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
];
?>
<div class="signing-record-view">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                if ($model->type == 1)
                {
                    echo 'mom';
                }
                else
                {
                    echo 'child';
                }

                ?>
                <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                            'id',
            'userid',
            'type',
            'sign_item_id_from',
            'sign_item_id_to',
            'status',
            'info_pics',
            'remark',
            'createtime:datetime',
                ],
                ]) ?>

            </div>
        </div>
    </div>
</div>