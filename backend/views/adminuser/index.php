<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\rbac\AuthAdminuser;
use yii\bootstrap\Modal;
use yii\helpers\HtmlPurifier;

?>

<?php

$this->title = "管理员列表";
\common\helpers\HeaderActionHelper::$action = [
    ['url' => ['adminuser/index'], 'name' => "管理员列表"],
    ['url' => ['adminuser/add'], 'name' => "添加管理员"],
];

echo GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'userlogin',
        [
            'attribute' => 'is_admin',
            'value' => function ($data) {
                if ($data->is_admin == 0) {
                    return '否';
                } elseif ($data->is_admin == 1) {
                    return '是';
                }
            },
        ],
        'phone',
        'email',
        [
            'attribute' => 'createtime',
            'format' => ['date', 'php:Y-m-d']
        ],
        [
            'attribute' => 'status',
            'value' => function ($data) {
                if ($data->status == 0) {
                    return '正常';
                } elseif ($data->status == 1) {
                    return '禁用';
                }
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => [
                'width' => '100',
            ],
            'template' => '{update} {delete} {assign}',
            'buttons' => [
                'assign' => function ($url, $model, $key) {
                    //return \yii\helpers\Html::a('', $url, ['class' => 'btn-icon-only icon-list', 'title' => '分配角色']);
                    return \yii\helpers\Html::a('', $url,
                        [
                            'class' => 'btn-icon-only icon-list', //样式（案例为按钮样式）
                            'data-target' => "#addchild",//关联模拟框(模拟框的ID)
                            'data-toggle' => "modal", //定义为模拟框 触发按钮
                            'xywy_id' => $model->id, //自定义属性(根据模拟框属性xywy_attr 获取此值)
                            'title' => '分配角色',
                        ]);
                }
            ],
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
