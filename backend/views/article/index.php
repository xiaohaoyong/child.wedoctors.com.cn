<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '宣教指导';
$this->params['breadcrumbs'][] = $this->title;
\common\helpers\HeaderActionHelper::$action=[
0=>['name'=>'添加','url'=>['create']]
];
?>
<div class="article-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <hr>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'columns' => [

            'id',
            [
                'attribute' => '标题',
                'value' => function($e)
                {
                    return $e->info->title;
                }
            ],
            [
                'attribute' => 'catid',
                'value' => function($e)
                {
                    return \common\models\Article::$catText[$e->catid];
                }
            ],
            [
                'attribute' => 'child_type',
                'value' => function($e)
                {
                    return \common\models\Article::$childText[$e->child_type];
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
                </div>
                ',
                'buttons'=>[
                    'push' =>function($url,$model,$key){
                        return \yii\helpers\Html::a('<span class="fa fa-share"> PUSH</span>', '#',
                            [
                                'data-target' => "#push",//关联模拟框(模拟框的ID)
                                'data-toggle' => "modal", //定义为模拟框 触发按钮
                                'data-id' => $model-id,
                                'class' => 'data-update',

                                'title' => 'PUSH',
                            ]);
                    }
                ]
            ],
        ],
    ]); ?>
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
echo $form->field($model,'id')->hiddenInput();
//echo $form->field($model,'area')->checkboxList(array_merge([0=>'全部'],\common\models\Area::$county[11]));
echo $form->field($model,'hospital')->checkboxList(array_merge([0=>'全部'],\common\models\UserDoctor::find()->select('name')->indexBy('userid')->column()));
echo $form->field($model,'age')->checkboxList(\common\models\Article::$childText);

echo "<div class=\"form-group\">".Html::submitButton('提交', ['class' => 'btn btn-primary'])."</div>";
\yii\widgets\ActiveForm::end();
Modal::end();
?>