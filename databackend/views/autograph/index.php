<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\AutographSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="autograph-index">
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
                                'id',
                                'createtime:datetime',
                                [
                                    'attribute' => 'img',
                                    'format' => 'html',
                                    'value' => function ($e) {
                                        return Html::img($e->img, ['width' => '100px']);
                                    }
                                ],
                                [
                                    'attribute' => 'userid',
                                    'value' => function ($e) {
                                        $return='';
                                        $userParent=\common\models\UserParent::findOne(['userid'=>$e->userid]);
                                        if($userParent){
                                            $return.="母亲:".$userParent->mother."|";
                                            $return.="父亲:".$userParent->father."|";
                                            $child=\common\models\ChildInfo::find()->select('name')->where(['userid'=>$e->userid])->column();
                                            $return.="孩子:".implode(',',$child);
                                        }
                                        return $return;
                                    }
                                ],

                                [
                                    'class' => 'common\components\grid\ActionColumn',
                                    'template' => '
                            <div class="btn-group dropup">
                                <a class="btn btn-circle btn-default btn-sm" target="_blank" href="javascript:;" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>{down}</li> <li>{downx}</li>
                                </ul>
                            </div>
                            ', 'buttons' => [
                                    'down' => function ($url, $model, $key) {
                                        return Html::a('<span class="fa fa-database"></span> 仅签字协议', \yii\helpers\Url::to(['autograph/down', 'userid' => $model->userid]),['target='=>'_blank']);
                                    },
                                    'downx' => function ($url, $model, $key) {
                                        return Html::a('<span class="fa fa-database"></span> 完整协议', \yii\helpers\Url::to(['autograph/down', 'userid' => $model->userid,'type'=>1]),['target='=>'_blank']);
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