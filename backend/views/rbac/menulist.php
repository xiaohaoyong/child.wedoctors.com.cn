<?php

use yii\grid\GridView;

$this->title = "菜单列表 ";
\common\helpers\HeaderActionHelper::$action = [
    ['url' => ['rbac/menu-list'], 'name' => "菜单列表"],
    ['url' => ['rbac/add-menu'], 'name' => "菜单添加"],
];

echo GridView::widget(['dataProvider' => $dataProvider,
    'columns' => [
        'name',
        'description',
        'sort',
        [
            'attribute' => 'display',
            'value' => function ($data) {
                if ($data->display == 0) {
                    return '不显示';
                } elseif ($data->display == 1) {
                    return '显示';
                }
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => ['width' => '100'],
            'template' => '{update} {delete} {submenu}',
            'buttons' => [
                'submenu' => function($url, $model, $key) {
                    return \yii\helpers\Html::a('', $url, [
                                'class' => 'btn-icon-only icon-list', //样式（案例为按钮样式）
                                'title' => '子菜单',
                    ]);
                },
                    ]
                ],
            ]
        ]);
?>