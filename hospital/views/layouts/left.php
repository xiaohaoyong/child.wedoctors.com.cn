<aside class="main-sidebar">

    <section class="sidebar">

        <?php if (in_array(Yii::$app->user->identity->county, [1105, 1106, 1109,1116]) || in_array(Yii::$app->user->identity->hospitalid, [110587, 110568, 110586, 110582, 110583, 110584, 110589, 110571, 110590, 110591, 110593, 110592, 110594, 110595, 110596, 110597, 110598, 110599, 110602, 110601, 110603, 110604, 110605, 110606, 110607, 110608, 110609, 110610, 110613, 110614, 110615, 110616, 110617, 110618, 110620, 110624, 110625, 110626, 110628, 110629, 110630, 110632, 110634])) {
            $a = ['label' => '儿童家医签约记录', 'icon' => 'pencil-square-o', 'url' => \yii\helpers\Url::to(['autograph/index']),];
            $b = ['label' => '孕产期管理', 'icon' => 'heart', 'url' => '#',
                'items' => [
                    ['label' => '追访记录', 'url' => ['interview/index']],
                    ['label' => '孕产期家医签约记录', 'url' => ['autograph/index', 'AutographSearch[t]' => 1]],
                    ['label' => '孕产妇数据', 'url' => ['/pregnancy']],
                ]
            ];
        } elseif (in_array(Yii::$app->user->identity->hospitalid, [110578, 110581])) {
            $a = ['label' => '儿童家医签约记录', 'icon' => 'pencil-square-o', 'url' => \yii\helpers\Url::to(['autograph/index']),];
        } elseif (in_array(Yii::$app->user->identity->hospitalid, [110543, 110552])) {
            $b = ['label' => '孕产期管理', 'icon' => 'heart', 'url' => '#',
                'items' => [
                    ['label' => '追访记录', 'url' => ['interview/index']],
                    ['label' => '孕产妇数据', 'url' => ['/pregnancy']],
                ]
            ];
        }
        $d = ['label' => '中医指导库', 'icon' => 'file-text-o', 'url' => \yii\helpers\Url::to(['article/zindex?ArticleSearchModel[type]=1']),
            'items' => [
                ['label' => '指导文章列表', 'url' => ['article/zindex?ArticleSearchModel[type]=1']],
                ['label' => '宣教任务（设置自动）', 'url' => ['/article-send']],
            ]
        ];
        if (Yii::$app->user->identity->hospitalid != 110587) {
            $c = ['label' => '数据同步（测试版）', 'url' => ['/synchronization/data']];

            $e = ['label' => '同步已签约数据', 'url' => ['/synchronization']];
        }

        if(in_array(Yii::$app->user->identity->hospitalid,[110647,110565,110598])){
            $health_records=['label' => '6岁以上学生签约', 'icon' => 'file-text-o', 'url' => "#",
                'items' => [
                    ['label' => '签约列表', 'url' => '/health-records'],
                    ['label' => '管辖学校管理', 'url' => ['/health-records-school']],
                    ['label' => '添加学校', 'url' => ['/health-records-school/create']],
                ]
            ];
        }



        ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => '管理目录', 'options' => ['class' => 'header']],
                    ['label' => '仪表盘', 'icon' => 'dashboard', 'url' => \yii\helpers\Url::to(['site/index']),],

                    ['label' => '管辖儿童', 'icon' => 'archive', 'url' => \yii\helpers\Url::to(['child-info/index']),],
                    $a,
                    $b,
                    ['label' => '社区数据统计', 'icon' => 'hospital-o', 'url' => \yii\helpers\Url::to(['user-doctor/index']),],
                    $d,
                    ['label' => '健康知识库', 'icon' => 'file-text-o', 'url' => "#",
                        'items' => [
                            ['label' => '文章列表', 'url' => ['article/index']],
                            ['label' => '添加文章', 'url' => ['article/create']],
                        ]
                    ],
                    ['label' => '工具', 'icon' => 'send', 'url' => "#",
                        'items' => [
                            ['label' => '留言板', 'url' => ['/question']],

                            ['label' => '提醒体检通知设置', 'url' => ['examination/automatic']],
                            ['label' => '未体检用户查询', 'url' => ['examination/undone']],
                            ['label' => '通知列表', 'url' => ['article/tindex?ArticleSearchModel[type]=2']],
                            ['label' => '发布通知', 'url' => ['article/tongzhi']],
                            ['label' => '平台召回用户统计', 'url' => ['/push-log']],
                            ['label' => '同步已签约数据', 'url' => ['/synchronization']],
                            $c,
                            ['label' => '疫情调查表下载', 'url' => ['/yiqing/down']],

                        ]
                    ],
                    ['label' => '预约系统管理', 'icon' => 'file-text-o', 'url' => "#",
                        'items' => [
                            ['label' => '预约系统设置', 'url' => ['hospital-appoint/index']],
                            ['label' => '预约列表', 'url' => ['appoint/index']],
                            ['label' => '街道管理', 'url' => ['street/index']],
                            ['label' => '添加街道', 'url' => ['street/create']],
                            ['label' => '加号', 'url' => ['child/signed']],
                            ['label' => '线上叫号', 'url' => ['appoint-calling/index']],

                        ]
                    ],
                    $health_records,
                    ['label' => '医生管理', 'icon' => 'file-text-o', 'url' => "#",
                        'items' => [
                            ['label' => '家医团队管理', 'url' => ['doctor-team/index']],
                            ['label' => '医生管理', 'url' => ['doctors/index']],
                            ['label' => '添加医生', 'url' => ['doctors/create']],
                        ]
                    ],

                ],
            ]
        ) ?>

    </section>

</aside>
