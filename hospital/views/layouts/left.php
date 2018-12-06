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
                    ['label' => '社区管理','icon' => 'hospital-o','url' => \yii\helpers\Url::to(['user-doctor/index']),],
                    ['label' => '中医指导库','icon' => 'file-text-o','url' => \yii\helpers\Url::to(['article/zindex?ArticleSearchModel[type]=1']),
                        'items' => [
                            ['label' => '指导文章列表', 'url' => ['article/zindex?ArticleSearchModel[type]=1']],
                            ['label' => '宣教任务（设置自动）', 'url' => ['/article-send']],
                        ]
                    ],
                    ['label' => '健康知识库','icon' => 'file-text-o','url' => "#",
                        'items' => [
                            ['label' => '文章列表', 'url' => ['article/index']],
                            ['label' => '添加文章', 'url' => ['article/create']],
                        ]
                    ],
                    ['label' => '工具','icon' => 'send','url' => "#",
                        'items' => [
                            ['label' => '通知列表', 'url' => ['article/tindex?ArticleSearchModel[type]=2']],
                            ['label' => '发布通知', 'url' => ['article/tongzhi']],
                            ['label' => '平台召回用户统计', 'url' => ['/push-log']],
                        ]
                    ],
                    ['label' => '预约系统管理','icon' => 'file-text-o','url' => "#",
                        'items' => [
                            ['label' => '预约系统设置', 'url' => ['user-doctor-appoint/index']],
                            ['label' => '预约列表', 'url' => ['appoint/index']],
                        ]
                    ],
                    ['label' => '医生管理','icon' => 'file-text-o','url' => "#",
                        'items' => [
                            ['label' => '医生管理', 'url' => ['doctors/index']],
                            ['label' => '添加医生', 'url' => ['doctors/create']],
                        ]
                    ],

                ],
            ]
        ) ?>

    </section>

</aside>
