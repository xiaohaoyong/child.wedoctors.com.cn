<aside class="main-sidebar">

    <section class="sidebar">


        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => '管理目录', 'options' => ['class' => 'header']],
                    ['label' => '仪表盘','icon' => 'dashboard','url' => \yii\helpers\Url::to(['site/index']),],
                    ['label' => '管辖儿童','icon' => 'archive','url' => \yii\helpers\Url::to(['child-info/index']),],
                    ['label' => '社区管理','icon' => 'hospital-o','url' => \yii\helpers\Url::to(['user-doctor/index']),],
                    ['label' => '宣教指导库','icon' => 'file-text-o','url' => "#",
                        'items' => [
                            ['label' => '列表', 'options' => ['class' => 'header'],'url' => \yii\helpers\Url::to(['article/index'])],
                            ['label' => '添加', 'options' => ['class' => 'header'],'url' => \yii\helpers\Url::to(['article/create'])],
                        ]
                    ],
                    ['label' => '中医知识库','icon' => 'file-text-o','url' => "#",
                        'items' => [
                            ['label' => '列表', 'options' => ['class' => 'header'],'url' => \yii\helpers\Url::to(['article/zindex?ArticleSearchModel[subject_pid]=7'])],
                            ['label' => '添加', 'options' => ['class' => 'header'],'url' => \yii\helpers\Url::to(['article/zhongyi'])],
                        ]
                    ],

                    ['label' => '工具','icon' => 'send','url' => "#",
                        'items' => [
                            ['label' => '通知列表', 'options' => ['class' => 'header'],'url' => \yii\helpers\Url::to(['article/tindex?ArticleSearchModel[catid]=6'])],
                            ['label' => '发布通知', 'options' => ['class' => 'header'],'url' => \yii\helpers\Url::to(['article/tongzhi'])],
                        ]
                    ],

                ],
            ]
        ) ?>

    </section>

</aside>
