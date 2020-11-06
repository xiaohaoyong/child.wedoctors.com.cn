<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\HealthRecordsSchool */

$this->title = '详情';
$this->params['breadcrumbs'][] = ['label' => 'Health Records Schools', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'列表','url'=>['index']],
1=>['name'=>'添加','url'=>['create']]
];
?>
<div class="health-records-school-view">
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
                            'name',
                            'doctorid',
                            'sign1',
                            'sign2',
                            'doctor_name',
                    [
                        'attribute' => '校医签字链接',
                        'value' => function($e)
                        {

                            $sign=md5('xiaoyi'.$e->id."9346)1f?]q63".date('Ymd'));
                            return 'http://web.child.wedoctors.com.cn/health-records/sign?type=xiaoyi&id='.$e->id."&sign=".$sign;
                        }
                    ],
                    [
                        'attribute' => '医生签字链接',
                        'value' => function($e)
                        {
                            $sign=md5('doctor'.$e->id."K1935.153f>-".date('Ymd'));
                            return 'http://web.child.wedoctors.com.cn/health-records/sign?type=doctor&id='.$e->id."&sign=".$sign;
                        }
                    ],
                    [
                        'attribute' => '备注',
                        'value' => function($e)
                        {
                            return '链接仅当日有效，请及时签字';
                        }
                    ],
                ],
                ]) ?>

            </div>
        </div>
    </div>
</div>