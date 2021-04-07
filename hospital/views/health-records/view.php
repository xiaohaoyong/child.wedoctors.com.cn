<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\HealthRecords */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Health Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="health-records-view">
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
            'field1',
            'field2',
            'field3',
            'field4',
            'field5',
            'field5_text',
            'field6',
            'field7',
            'field8',
            'field8_text',
            'field9',
            'field10',
            'field11',
            'field12',
            'field13',
            'field14',
            'field15',
            'field41',
            'field42',
            'field16',
            'field16_text',
            'field17',
            'field17_text',
            'field18',
            'field18_text',
            'field19',
            'field19_text',
            'field20',
            'field20_text',
            'field21',
            'field22',
            'field23',
            'field24',
            'field25',
            'field26',
            'field27',
            'field28',
            'field29',
            'field30',
            'field40',
            'field34',
            'field31',
            'field32',
            'field33',
            'field39',
            'field35',
            'field36',
            'field37',
            'field38',
            'createtime:datetime',
            'doctorid',
            'userid',
            'field43',
            'field44',
                ],
                ]) ?>

            </div>
        </div>
    </div>
</div>