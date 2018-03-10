<?php
/**
 * @var $this \yii\web\View
 */
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\rbac\AuthAdminuser;
use yii\bootstrap\Modal;
use yii\helpers\HtmlPurifier;

?>

<?php

$this->title = "角色列表 ";
\common\helpers\HeaderActionHelper::$action = [
    ['url' => ['rbac/roles'], 'name' => "角色列表"],
    ['url' => ['rbac/add-role'], 'name' => "角色添加"],
];

echo GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        'description',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => ['width' => '100'],
            'template' => '{update} {delete} {assign}',
            'buttons' => [
                'assign' => function ($url, $model, $key) {
                    return \yii\helpers\Html::a('', \Yii::$app->urlManager->createUrl(['rbac/add-child', 'id'=>$model->name]),
                        [
                            'class' => 'btn-icon-only icon-list', //样式（案例为按钮样式）
                            'data-target' => "#addchild",//关联模拟框(模拟框的ID)
                            'data-toggle' => "modal", //定义为模拟框 触发按钮
                            'xywy_id' => $model->name, //自定义属性(根据模拟框属性xywy_attr 获取此值)
                            'title' => '分配权限',
                        ]);
                },
                'update' => function ($url, $model, $key) {
                    return \yii\helpers\Html::a('', \Yii::$app->urlManager->createUrl(['rbac/update-role', 'id'=>$model->name]),
                        [
                            'class' => 'glyphicon glyphicon-pencil', //样式（案例为按钮样式）
                        ]);
                },
                'delete' => function($url, $model, $key){
                    return Html::a('', yii\helpers\Url::to(['rbac/remove-role', 'id'=>$model->name]), ['class'=>'glyphicon glyphicon-trash', 'title'=>'删除', 'data'=>['confirm'=>'您确定要删除此项吗？'],]);
                },
            ]
        ],
    ]
]);
\common\widgets\ModalWidget::begin([
    'id' => 'addchild',//弹出层ID
    'xywy_attr' => ['id'], //需要进行传输的字段改name值
]);

\common\widgets\ModalWidget::end(); //结束
$this->registerJs("
$('#addchild').data('bs.modal','');
");
?>


