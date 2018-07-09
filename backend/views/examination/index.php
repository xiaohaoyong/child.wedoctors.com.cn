<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ExaminationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="examination-index">
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
                                'id',
                                'childid',
                                'field1',
                                'field4',
                                // 'field5',
                                // 'field6',
                                // 'field7',
                                // 'field8',
                                 'field9',
                                // 'field10',
                                // 'field11',
                                // 'field12',
                                // 'field13',
                                // 'field14',
                                // 'field15',
                                // 'field16',
                                // 'field17',
                                // 'field18',
                                 'field19',
                                // 'field20',
                                // 'field21',
                                // 'field22',
                                // 'field23',
                                // 'field24',
                                // 'field25',
                                // 'field26',
                                // 'field27',
                                // 'field28',
                                // 'field29',
                                // 'field30',
                                // 'field31',
                                // 'field32',
                                // 'field33',
                                // 'field34',
                                // 'field35',
                                // 'field36',
                                // 'field37',
                                // 'field38',
                                // 'field39',
                                // 'field40',
                                // 'field41',
                                // 'field42',
                                // 'field43',
                                // 'field44',
                                // 'field45',
                                // 'field46',
                                // 'field47',
                                // 'field48',
                                // 'field49',
                                // 'field50',
                                // 'field51',
                                 'field52',
                                // 'field53',
                                // 'field54',
                                // 'field55',
                                // 'field56',
                                // 'field57',
                                // 'field58',
                                // 'field59',
                                // 'field60',
                                // 'field61',
                                // 'field62',
                                // 'field63',
                                // 'field64',
                                // 'field65',
                                // 'field66',
                                // 'field67',
                                // 'field68',
                                // 'field69',
                                // 'field70',
                                // 'field71',
                                // 'field72',
                                // 'field73',
                                // 'field74',
                                // 'field75',
                                // 'field76',
                                // 'field77',
                                // 'field78',
                                // 'field79',
                                // 'field80',
                                // 'field81',
                                // 'field82',
                                // 'field83',
                                // 'field84',
                                // 'field85',
                                // 'field86',
                                // 'field87',
                                // 'field88',
                                // 'field89',
                                // 'field90',
                                // 'field91',
                                // 'field92',
                                // 'source',
                                // 'isupdate',

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

                                </ul>
                            </div>
                            ',
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>