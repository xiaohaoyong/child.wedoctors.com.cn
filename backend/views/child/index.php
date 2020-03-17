<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '儿童数据';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="child-info-index">
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
                                'name',
                                'birthday:datetime',
                                'createtime:datetime',
                                [

                                    'attribute' => 'source',
                                    'value' => function ($e) {
                                        if ($e->source) {
                                            return \common\models\UserDoctor::findOne(['hospitalid' => $e->source])->name;
                                        } else {
                                            return "无";
                                        }
                                    }
                                ],
                                [
                                    'attribute' => 'admin',
                                    'format' => 'raw',
                                    'value' => function ($e) {
                                        $doctor = \common\models\UserDoctor::findOne(['hospitalid' => $e->admin]);

                                        return $doctor->name;

                                    }
                                ],//             'field54',
//             'field53',
//             'field52',
//             'field51',
//             'field50',
//             'field49',
//             'field48',
//             'field47',
//             'field46',
//             'field45',
                                // 'field44',
                                // 'field43',
                                // 'field42',
                                // 'field41',
                                // 'field40',
                                // 'field39',
                                // 'field38',
                                // 'field37',
                                // 'field27',
                                // 'field26',
                                // 'field25',
                                // 'field24',
                                // 'field23',
                                // 'field22',
                                // 'field21',
                                // 'field20',
                                // 'field19',
                                // 'field18',
                                // 'field17',
                                // 'field16',
                                // 'field15',
                                // 'field14',
                                // 'field13',
                                'field7',
                                // 'field6',
                                // 'field0',
                                // 'doctorid',

                                [
                                    'class' => 'common\components\grid\ActionColumn',
                                    'template' => '
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>{update}</li>
                                    <li>{delete}</li>
                                                                        <li>{view}</li>
                                                                        <li>{tijian}</li>
                                                                        <li>{auto}</li>
                                                                                                                                                <li>{parent}</li>


                                </ul>
                            </div>
                            ',
                                    'buttons' => [
                                        'tijian' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-database"></span> 查看关联体检', \yii\helpers\Url::to(['examination/index', 'ExaminationSearch[childid]' => $model->id]));
                                        },
                                        'parent' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-database"></span> 家庭数据', \yii\helpers\Url::to(['user-parent/index', 'UserParentSearch[userid]' => $model->userid]));
                                        },
                                        'auto' => function ($url, $model, $key) {
                                            return Html::a('<span class="fa fa-database"></span> 签字', \yii\helpers\Url::to(['autograph/index', 'AutographSearch[userid]' => $model->userid]));
                                        },
                                    ],
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>