<aside class="main-sidebar">

    <section class="sidebar">


        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => '管理目录', 'options' => ['class' => 'header']],
                    [
                        'label' => '用户管理',
                        'icon' => 'user',
                        'url' => '#',
                        'items' => [
                            ['label' => '账户管理', 'icon' => 'list', 'url' => ['/user'],],

                        ],
                    ],
                    [
                        'label' => '数据管理',
                        'icon' => 'database',
                        'url' => '#',
                        'items' => [
                            ['label' => '医院管理', 'icon' => 'list', 'url' => ['/hospital'],],
                            ['label' => '儿童列表', 'icon' => 'list', 'url' => ['/child-info'],],
                            ['label' => '医生列表', 'icon' => 'list', 'url' => ['/user-doctor'],],
                            ['label' => '宣教指导文章管理', 'icon' => 'list', 'url' => ['/article'],],
                            ['label' => '轮播图', 'icon' => 'list', 'url' => ['/carousel'],],
                            ['label' => '数据后台账号', 'icon' => 'list', 'url' => ['/data-user'],],
                            ['label' => '聊天消息', 'icon' => 'list', 'url' => ['/chat-record-content'],],
                            ['label' => '医院管理', 'icon' => 'list', 'url' => ['/hospital'],],
                        ],
                    ],
                    [
                        'label' => '功能',
                        'icon' => 'database',
                        'url' => '#',
                        'items' => [
                            ['label' => '外链推送', 'icon' => 'list', 'url' => ['/chain'],],

                        ],
                    ],
                    [
                        'label' => '系统应用',
                        'icon' => 'file-code-o',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
