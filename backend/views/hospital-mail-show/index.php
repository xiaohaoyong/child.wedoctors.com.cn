<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\HospitalMailShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '通知查看情况';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hospital-mail-show-index">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,

                            'columns' => [
                                [

                                    'attribute' => 'hospitalid',
                                    'value' => function ($e) {
                                        return $e->hospitalid?\common\models\UserDoctor::findOne(['hospitalid'=>$e->hospitalid])->name:"全部";

                                    }
                                ],
                                'createtime:datetime',

                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>