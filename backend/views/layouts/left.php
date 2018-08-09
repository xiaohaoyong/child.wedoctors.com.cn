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
                            ['label' => '账户管理', 'icon' => 'user', 'url' => ['/user'],],
                            ['label' => '用户登录信息', 'icon' => 'sign-in', 'url' => ['/user-login'],],
                            ['label' => '扫码记录', 'icon' => 'wechat', 'url' => ['/we-openid'],],


                        ],
                    ],
                    [
                        'label' => '数据管理',
                        'icon' => 'database',
                        'url' => '#',
                        'items' => [
                            ['label' => '医院管理', 'icon' => 'hospital-o', 'url' => ['/hospital'],],
                            ['label' => '儿童列表', 'icon' => 'child', 'url' => ['/child-info'],],
                            ['label' => '家庭数据', 'icon' => 'child', 'url' => ['/user-parent'],],
                            ['label' => '儿童数据', 'icon' => 'child', 'url' => ['/child'],],
                            ['label' => '儿童体检数据', 'icon' => 'heartbeat', 'url' => ['/examination'],],

                            ['label' => '医生列表', 'icon' => 'building-o', 'url' => ['/user-doctor'],],
                            ['label' => '宣教指导文章管理', 'icon' => 'list', 'url' => ['/article'],],
                            ['label' => '宣教指导分类管理', 'icon' => 'list', 'url' => ['/article-category'],],

                            ['label' => '轮播图', 'icon' => 'list', 'url' => ['/carousel'],],
                            ['label' => '数据后台账号', 'icon' => 'list', 'url' => ['/data-user'],],
                            ['label' => '聊天消息', 'icon' => 'comments', 'url' => ['/chat-record-content'],],
                            ['label' => '育儿指南', 'icon' => 'comments','url' => '#',
                                'items'=>[
                                    ['label' => '指南类别', 'icon' => 'hospital-o', 'url' => ['/baby-tool-tag'],],
                                    ['label' => '指南内容', 'icon' => 'hospital-o', 'url' => ['/baby-tool'],],

                                ],],

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
