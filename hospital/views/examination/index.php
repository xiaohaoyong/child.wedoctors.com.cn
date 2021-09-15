<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel hospital\models\ExaminationModels */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '门诊日志';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action = [
    0 => ['name' => '添加', 'url' => ['create']]
];
?>
<div class="examination-index">
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
                                'field4',
                                'field1',
                                'field32',
                                'field19',
                                [
                                    'attribute' => '实足年龄',
                                    'value' => function($e)
                                    {
                                        return $e->field2."岁".$e->field3."月";
                                    }
                                ],
                                [
                                    'attribute' => '现住址',
                                    'value' => function($e)
                                    {
                                        return $e->child->parent->fieldu46;
                                    }
                                ],
                                [
                                    'attribute' => '联系电话',
                                    'value' => function($e)
                                    {
                                        if(!$e->child->parent->mother_phone){
                                            $userlogin=\common\models\UserLogin::findOne(['userid'=>$e->child->parent->userid]);
                                            if($userlogin){
                                                $phone=$userlogin->phone;
                                            }else{
                                                $phone='';
                                            }
                                        }else{
                                            $phone=$e->child->parent->mother_phone;
                                        }
                                        return $phone;
                                    }
                                ],
                                [
                                    'attribute' => '一般体检',
                                    'value' => function($e)
                                    {
                                        return "✔️";
                                    }
                                ],
                                [
                                    'attribute' => '血清素',
                                    'value' => function($e)
                                    {
                                        return $e->field41?"✔️":"";
                                    }
                                ],
                                [
                                    'attribute' => 'field71',
                                    'value' => function($e)
                                    {
                                        return $e->field71?"✔️":"";
                                    }
                                ],
                                [
                                    'attribute' => 'ddst',
                                    'value' => function($e)
                                    {
                                        return "";
                                    }
                                ],
                                [
                                    'attribute' => '口腔检查',
                                    'value' => function($e)
                                    {
                                        return $e->field48?"✔️":"";
                                    }
                                ],
                                [
                                    'attribute' => '视力检查',
                                    'value' => function($e)
                                    {
                                        return $e->field77|| $e->field78?"✔️":"";
                                    }
                                ],
                                [
                                    'attribute' => '听力损失新发',
                                    'value' => function($e)
                                    {
                                        return $e->field64?"✔️":"";
                                    }
                                ],
                                [
                                    'attribute' => '先心病新发',
                                    'value' => function($e)
                                    {
                                        return $e->field37?"✔️":"";
                                    }
                                ],
                                [
                                    'attribute' => 'field42',
                                    'value' => function($e)
                                    {
                                        return $e->field42?"✔️":"";
                                    }
                                ],
                                [
                                    'attribute' => '中医指导',
                                    'value' => function($e)
                                    {
                                        return $e->field86=='是'?"✔️":"";
                                    }
                                ],
                                [
                                    'attribute' => '中医实指导',
                                    'value' => function($e)
                                    {
                                        return $e->field86=='是'?"✔️":"";
                                    }
                                ],
                                'field8',

                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>