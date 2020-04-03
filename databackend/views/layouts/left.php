<aside class="main-sidebar">

    <section class="sidebar">

        <?php
        if(Yii::$app->user->identity->county==1105){
            $a=['label' => '签名记录','icon' => 'pencil-square-o','url' => \yii\helpers\Url::to(['autograph/index']),];
        }
        ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => '管理目录', 'options' => ['class' => 'header']],
                    ['label' => '仪表盘','icon' => 'dashboard','url' => \yii\helpers\Url::to(['site/index']),],
                    ['label' => '管辖儿童','icon' => 'archive','url' => \yii\helpers\Url::to(['child-info/index']),],
                    $a,
                    ['label' => '统计数据','icon' => 'file-text-o','url' => "#",
                        'items' => [
                            ['label' => '社区预约情况', 'options' => ['class' => 'header'],'url' => \yii\helpers\Url::to(['appoint/hospital'])],
                        ]
                    ],
                    ['label' => '宣教记录','icon' => 'database','url' => \yii\helpers\Url::to(['article-user/index']),],
                    ['label' => '社区管理','icon' => 'hospital-o','url' => \yii\helpers\Url::to(['user-doctor/index']),],
                    ['label' => '中医指导库','icon' => 'file-text-o','url' => \yii\helpers\Url::to(['article/zindex?ArticleSearchModel[type]=1'])],
                    ['label' => '宣教知识库','icon' => 'file-text-o','url' => "#",
                        'items' => [
                            ['label' => '列表', 'options' => ['class' => 'header'],'url' => \yii\helpers\Url::to(['article/index'])],
                            ['label' => '添加', 'options' => ['class' => 'header'],'url' => \yii\helpers\Url::to(['article/create'])],
                        ]
                    ],
                    ['label' => '工具','icon' => 'send','url' => "#",
                        'items' => [
                            ['label' => '通知列表', 'options' => ['class' => 'header'],'url' => \yii\helpers\Url::to(['article/tindex?ArticleSearchModel[type]=2'])],
                            ['label' => '发布通知', 'options' => ['class' => 'header'],'url' => \yii\helpers\Url::to(['article/tongzhi'])],
                        ]
                    ],

                ],
            ]
        ) ?>

    </section>

</aside>
