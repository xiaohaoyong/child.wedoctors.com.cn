<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DoctorsSearchModels */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '医生列表';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="doctors-index">
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
                                'name',
                                [
                                    'attribute' => '手机号',
                                    'value' => function ($e) {
                                        $user=\common\models\User::findOne($e->userid);
                                        return $user->phone;
                                    }
                                ],
                                [
                                    'attribute' => '医生权限',
                                    'value' => function ($e) {
                                        $return=[];
                                        foreach(\common\models\Doctors::$typeText as $v){
                                            var_dump($v);die;
                                            if($e->type && ($e->type|bindec($v))==$e->type){
                                                $return[]=$v;
                                            }
                                        }
                                        return implode(',',$return);
                                    }
                                ],
//            'sex',
//            'age',
//            'birthday',
                                // 'hospitalid',
                                // 'subject_b',
                                // 'subject_s',
                                // 'title',
                                // 'intro',
                                // 'avatar',
                                // 'skilful',
                                // 'idnum',
                                // 'province',
                                // 'county',
                                // 'city',
                                // 'atitle',
                                // 'otype',
                                // 'authimg',
                                // 'type',

                                [
                                    'class' => 'common\components\grid\ActionColumn',
                                    'template' => '
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>{update}</li>
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