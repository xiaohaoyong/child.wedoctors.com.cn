<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleCommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理列表';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="article-comment-index">
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
                                'artid',
                                'content',
                                'userid',
                                [
                                    'attribute' => '儿童姓名',
                                    'value' => function ($e) {
                                        $child=\common\models\ChildInfo::findOne(['userid'=>$e->userid]);
                                        return $child->name;
                                    }
                                ],
                                [
                                    'attribute' => '儿童性别',
                                    'value' => function ($e) {
                                        $child=\common\models\ChildInfo::findOne(['userid'=>$e->userid]);
                                        return \common\models\ChildInfo::$genderText[$child->gender];
                                    }
                                ],
                                [
                                    'attribute' => '儿童出生日期',
                                    'value' => function ($e) {
                                        $child=\common\models\ChildInfo::findOne(['userid'=>$e->userid]);
                                        return date('Y-m-d',$child->birthday);
                                    }
                                ],
                                [
                                    'attribute' => '联系电话',
                                    'value' => function ($e) {
                                        $user=\common\models\UserLogin::find()->where(['userid'=>$e->userid])->andWhere(['>','phone',0])->one();
                                        return $user->phone;
                                    }
                                ],
                                'createtime:datetime',

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