<aside class="main-sidebar">

    <section class="sidebar">


        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => '管理目录', 'options' => ['class' => 'header']],
                    [
                        'label' => '用户管理',
                        'icon' => 'user',
                        'url' => '#',
                        'items' => [
                            ['label' => '账户管理', 'icon' => 'user', 'url' => ['/user'],],
                            ['label' => '用户登录信息', 'icon' => 'sign-in', 'url' => ['/user-login'],],
                            ['label' => '扫码记录', 'icon' => 'wechat', 'url' => ['/we-openid'],],


                        ],
                    ],
                    [
                        'label' => '留言板',
                        'icon' => 'user',
                        'url' => '#',
                        'items' => [
                            ['label' => '问题列表', 'icon' => 'user', 'url' => ['/question'],],
                        ],
                    ],
                    [
                        'label' => '评价管理',
                        'icon' => 'comments',
                        'url' => '#',
                        'items' => [
                            ['label' => '预约就诊评价',  'url' => ['/question'],],
                            ['label' => '医生回复评价',  'url' => ['/question-comment'],],
                        ],
                    ],
                    [
                        'label' => '数据管理',
                        'icon' => 'database',
                        'url' => '#',
                        'items' => [
                            ['label' => '医院管理', 'icon' => 'hospital-o', 'url' => ['/hospital'],],
                            ['label' => '社区医院列表', 'icon' => 'building-o', 'url' => ['/user-doctor'],],
                            ['label' => '医生列表', 'icon' => 'building-o', 'url' => ['/doctors'],],

                            ['label' => '家庭数据', 'icon' => 'child', 'url' => ['/user-parent'],],
                            ['label' => '儿童列表', 'icon' => 'child', 'url' => ['/child-info'],],
                            ['label' => '儿童数据', 'icon' => 'child', 'url' => ['/child'],],
                            ['label' => '儿童体检数据', 'icon' => 'heartbeat', 'url' => ['/examination'],],

                            ['label' => '宣教指导文章管理', 'icon' => 'list', 'url' => ['/article'],],
                            ['label' => '宣教指导分类管理', 'icon' => 'list', 'url' => ['/article-category'],],

                            ['label' => '签约管理', 'icon' => 'list', 'url' => ['/doctor-parent'],],
                            ['label' => '积分记录', 'icon' => 'list', 'url' => ['/points'],],
                            ['label' => '签到记录', 'icon' => 'list', 'url' => ['/clock-in'],],

                            ['label' => '数据上传记录', 'icon' => 'list', 'url' => ['/data-update-record'],],
                            ['label' => '预约列表', 'icon' => 'list', 'url' => ['/appoint/index'],],


                            ['label' => '孕产管理', 'icon' => 'comments', 'url' => '#',
                                'items' => [
                                    ['label' => '孕产数据', 'icon' => 'hospital-o', 'url' => ['/pregnancy'],],
                                    ['label' => '追访记录', 'icon' => 'hospital-o', 'url' => ['/interview'],],

                                ],],

                            ['label' => '育儿指南', 'icon' => 'comments', 'url' => '#',
                                'items' => [
                                    ['label' => '指南类别', 'icon' => 'hospital-o', 'url' => ['/baby-tool-tag'],],
                                    ['label' => '指南内容', 'icon' => 'hospital-o', 'url' => ['/baby-tool'],],
                                    ['label' => '指南内容', 'icon' => 'hospital-o', 'url' => ['/baby-guide'],],


                                ],],
                            ['label' => '疫苗数据', 'icon' => 'comments', 'url' => ['/vaccine'],],

                            ['label' => '签字记录', 'icon' => 'comments', 'url' => ['/autograph'],],

                        ],
                    ],
                    [
                        'label' => '功能',
                        'icon' => 'database',
                        'url' => '#',
                        'items' => [
                            ['label' => '外链推送', 'icon' => 'list', 'url' => ['/chain'],],
                            ['label' => '开通自动推送列表', 'icon' => 'list', 'url' => ['/push'],],
                            ['label' => '预约开通情况', 'icon' => 'list', 'url' => ['/hospital-appoint'],],
                            ['label' => '待审宣教指导', 'icon' => 'list', 'url' => ['/article/examine'],],
                            ['label' => '待发布宣教指导', 'icon' => 'list', 'url' => ['/article/release'],],
                            ['label' => '发送预约通知', 'icon' => 'list', 'url' => ['/appoint/indext'],],
                            ['label' => '轮播图', 'icon' => 'list', 'url' => ['/carousel'],],
                            ['label' => '数据后台账号', 'icon' => 'list', 'url' => ['/data-user'],],
                            ['label' => '聊天消息', 'icon' => 'comments', 'url' => ['/chat-record-content'],],
                            [
                                'label' => '通知中心',
                                'icon' => 'user',
                                'url' => '#',
                                'items' => [
                                    ['label' => '通知列表', 'icon' => 'user', 'url' => ['/hospital-mail'],],
                                    ['label' => '通知查看情况', 'icon' => 'sign-in', 'url' => ['/hospital-mail-show'],],


                                ],
                            ],
                        ],
                    ],
                    [
                        'label' => '系统应用',
                        'icon' => 'file-code-o',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],],
                    ],
					[
						'label' => '迁入迁出管理', 
						'icon' => 'file-text-o', 
						'url' => "#",
                        'items' => [
                            ['label' => '迁入迁出成功历史记录', 'url' => ['xlsxoutof/list-info']],
                        ]
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
