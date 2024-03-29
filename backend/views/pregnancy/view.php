<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Pregnancy */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Pregnancies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="pregnancy-view">
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
            'familyid',
            'field0',
            'field1',
            'field2',
            'field3',
            'field4',
            'field5',
            'field6',
            'field7',
            'field8',
            'field9',
            'field10',
            'field11',
            'field12',
            'field13',
            'field14',
            'field15',
            'field16',
            'field17',
            'field18',
            'field19',
            'field20',
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
            'field31',
            'field32',
            'field33',
            'field34',
            'field35',
            'field36',
            'field37',
            'field38',
            'field39',
            'field40',
            'field41',
            'field42',
            'field43',
            'field44',
            'field45',
            'field46',
            'field47',
            'field48',
            'field49',
            'field50',
            'field51',
            'field52',
            'field53',
            'field54',
            'field55',
            'field56',
            'field57',
            'field58',
            'field59',
            'field60',
            'field61',
            'field62',
            'field63',
            'field64',
            'field65',
            'field66',
            'field67',
            'field68',
            'field70',
            'field71',
            'field72',
            'field73',
            'field74',
            'field75',
            'field76',
            'field77',
            'field78',
            'field79',
            'field80',
            'field81',
            'field82',
            'field83',
            'field84',
            'field85',
            'field86',
            'field87',
            'field88',
            'field89',
            'source',
            'isupdate',
            'createtime:datetime',
            'doctorid',
            'field90',
                ],
                ]) ?>

            </div>
        </div>
    </div>
</div>