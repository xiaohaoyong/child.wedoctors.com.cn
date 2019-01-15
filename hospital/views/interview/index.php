<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\InterviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="interview-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">检索：</h3>
                <div>
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options' => ['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,

                            'columns' => [

                                [
                                    'attribute' => 'week',
                                    'value' => function ($e) {
                                        return \common\models\Interview::$weekText[$e->week];
                                    }
                                ],
                                [
                                    'attribute' => 'userid',
                                    'value' => function ($e) {
                                        $preg=\common\models\Pregnancy::findOne(['familyid'=>$e->userid]);
                                        return $preg?$preg->field1:"";
                                    }
                                ],
                                [
                                    'attribute' => '末次月经',
                                    'value' => function ($e) {
                                        $preg=\common\models\Pregnancy::findOne(['familyid'=>$e->userid]);
                                        return $preg?date('Y-m-d',$preg->field16):"";
                                    }
                                ],
                                [
                                    'attribute' => '户籍地址',
                                    'value' => function ($e) {
                                        $preg=\common\models\Pregnancy::findOne(['familyid'=>$e->userid]);
                                        return $preg?$preg->field7:"";
                                    }
                                ],
                                [
                                    'attribute' => 'prenatal_test',
                                    'value' => function ($e) {
                                        return \common\models\Interview::$prenatal_Text[$e->prenatal_test];
                                    }
                                ],
                                'pt_hospital',
                                'pt_date:datetime',
                                [
                                    'attribute' => 'prenatal',
                                    'value' => function ($e) {
                                        return \common\models\Interview::$prenatalText[$e->prenatal];
                                    }
                                ],
                                [
                                    'attribute' => 'pt_value',
                                    'value' => function ($e) {
                                        return \common\models\Interview::$prenatalValueText[$e->pt_value];
                                    }
                                ],
                                'childbirth_hospital',
                                'childbirth_date:datetime',
                                'createtime:datetime',
                                // 'userid',
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>