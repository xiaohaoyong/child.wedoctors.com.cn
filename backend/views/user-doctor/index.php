<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserDoctorSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parents common\models\UserParent */

$this->title = '医生管理';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'添加','url'=>['create']]
];
?>
<div class="user-doctor-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <hr>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'columns' => [

            [
                'attribute' => '姓名',
                'value' => function($data)
                {
                    return $data->name;
                }
            ],
            [
                    'attribute' => '职称',
                'value' => function($data)
                {
                    return \common\models\UserDoctor::$titleText[$data->title];
                }

            ],


            [
                'attribute' => '一级科室',
                'value' => function($data)
                {
                    return \common\models\Subject::$subject[$data->subject_b];
                }
            ],

            [
                'attribute' => '所属医院',
                'value' => function($data)
                {
                    $hospital=\common\models\Hospital::findOne($data->hospitalid);
                    return $hospital->name;
                }
            ],
            [
                'attribute' => '签约儿童数',
                'value' => function($data)
                {
                    $child=$data->getChild();
                    return $child->count();
                }
            ],
            [
                'attribute' => '宣教次数',
                'value' => function($data)
                {
                   return \common\models\ArticleUser::find()->where(['userid'=>$data->userid])->count();
                }
            ],
            [
                'class' => 'common\components\grid\ActionColumn',
                'template'=>'
                <div class="btn-group dropup">
                    <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>{update} </li><li>{delete}</li>
                    </ul>
                </div>
                ',
            ],
        ],
    ]); ?>
</div>
