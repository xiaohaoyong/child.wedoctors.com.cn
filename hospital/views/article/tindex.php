<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '通知';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'添加通知','url'=>['tongzhi']]
];
?>
<div class="article-index">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <?= GridView::widget([
                            'options'=>['class' => 'col-sm-12'],
                            'dataProvider' => $dataProvider,
                            'columns' => [

                                'id',
                                [
                                    'attribute' => '通知标题',
                                    'value' => function($e)
                                    {
                                        return $e->info->title;
                                    }
                                ],
                                [
                                    'attribute' => 'createtime',
                                    'format' => ['date', 'php:Y-m-d']
                                ],
                                // 'num',
                                // 'type',

                                [
                                    'class' => 'common\components\grid\ActionColumn',
                                    'template'=>'
                <div class="btn-group dropup">
                    <a class="btn btn-circle btn-default btn-sm" href="javascript:;" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-settings"></i> 操作 <i class="fa fa-angle-up"></i></a>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>{update} </li><li>{delete}</li><li>{push}</li>
                    </ul>
                </div>',
                                    'buttons'=>[
                                        'update'=>function($url,$model,$key){
                                            return Html::a('<span class="fa fa-database"></span> 修改',\yii\helpers\Url::to(['article/tongzhi','id'=>$model->id]));
                                        },
                                        'push' =>function($url,$model,$key){
                                            return \yii\helpers\Html::a('<span class="fa fa-share"> 推送</span>', '#',
                                                [
                                                    'data-target' => "#push",//关联模拟框(模拟框的ID)
                                                    'data-toggle' => "modal", //定义为模拟框 触发按钮
                                                    'data-id' => $model->id,
                                                    'class' => 'data-update',

                                                    'title' => 'PUSH',
                                                ]);
                                        }



                                    ],
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<?php
use yii\bootstrap\Modal;

Modal::begin([
    'id' => 'push',
    'header' => '<h5>PUSH对象</h5>',
]);

$updateJs = <<<JS
    $('.data-update').on('click', function () {
        var id=$(this).closest('tr').data('key');
        $('#push-id').val(id);
        console.log(id);
    });
JS;
$this->registerJs($updateJs);




$form = \yii\widgets\ActiveForm::begin(['action'=>'/article/push','enableAjaxValidation' => true, ]);
$model=new \backend\models\Push();
echo $form->field($model,'id')->hiddenInput()->label(false);
//echo $form->field($model,'area')->checkboxList(array_merge([0=>'全部'],\common\models\Area::$county[11]));
echo $form->field($model,'age')->checkboxList(\common\models\Article::$childText);

echo "<div class=\"form-group\">".Html::submitButton('提交', ['class' => 'btn btn-primary'])."</div>";
\yii\widgets\ActiveForm::end();
Modal::end();
?>