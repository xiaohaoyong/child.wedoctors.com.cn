<aside class="main-sidebar">

    <section class="sidebar">
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => '管理目录', 'options' => ['class' => 'header']],
                    ['label' => '仪表盘', 'icon' => 'dashboard', 'url' => \yii\helpers\Url::to(['site/index']),],
                    ['label' => '留言板', 'icon' => 'archive', 'url' => \yii\helpers\Url::to(['/question']),],
                ],
            ]
        ) ?>

    </section>

</aside>
