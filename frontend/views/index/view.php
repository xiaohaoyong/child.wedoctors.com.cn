<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/30
 * Time: 上午11:52
 */

use yii\bootstrap\Modal;

$this->title = "开放-合作-共享 儿宝宝";

?>
    <script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=<?= \Yii::$app->params['map_key'] ?>"></script>
    <div class="banner">
        <div class="main" style=" height:530px;background: url('/img/view_banner_img.png') no-repeat right;">
            <div class="text">
                <div class="title">开放 · 合作 · 共享</div>
                <div class="info">开放、合作、共享是儿宝宝一直以来秉承的理念，希望
                    携手各方合作伙伴一起从智能化管理、服务、效率工具等
                    多维度功能赋能基层医疗卫生，提供专业、便捷、优质、持续的
                    医疗健康服务及效能工具。
                </div>
            </div>

        </div>
    </div>
    <div class="content">
        <div class="list">
            <div class="item">
                <div class="img"><img src="/img/view_list1.png?t=123" width="75" height="71"></div>
                <div class="title">医疗知识服务</div>
                <div class="info" style="width: 127px;">开放第三方健康医疗 服务应用接入</div>
            </div>
            <div class="item">
                <div class="img"><img src="/img/view_list4.png" width="75" height="70"></div>
                <div class="title">企业和机构</div>
                <div class="info" style="width: 139px;">企业和机构的智能赋能 工具商业化接入</div>
            </div>
            <div class="item">
                <div class="img"><img src="/img/view_list2.png" width="64" height="71"></div>
                <div class="title">医疗健康保险</div>
                <div class="info" style="width: 99px;">商业保险平台及 健康险接入方案</div>
            </div>
            <div class="item">
                <div class="img"><img src="/img/view_list3.png" width="70" height="70 "></div>
                <div class="title">智慧终端</div>
                <div class="info" style="width: 99px;">开放第三方终端 接入方案</div>
            </div>
        </div>
        <div class="bottom" style="display: block; padding-right: 0; padding-left: 0; padding-top: 57px;">
            <div class="navigation">
                <div class="title">
                    <div class="text">合作</div>
                    <div class="en">Cooperation</div>
                </div>
                <div class="mapa" data-toggle='modal' data-target='#map-modal'>
                    <img src="/img/view_content_map.png" width="60" height="60">
                    <div class="text">点击查看地图</div>
                </div>
            </div>
            <ul class="county nav nav-tabs">
                <li class="item active"><a href="#list1" data-toggle="tab">西城区</a></li>
                <li class="item"><a href="#list2" data-toggle="tab">昌平区</a></li>
                <li class="item"><a href="#list3" data-toggle="tab">海淀区</a></li>
                <li class="item"><a href="#list4" data-toggle="tab">朝阳区</a></li>
                <li class="item"><a href="#list5" data-toggle="tab">东城区</a></li>
                <li class="item"><a href="#list6" data-toggle="tab">石景山</a></li>
                <li class="item"><a href="#list7" data-toggle="tab">房山区</a></li>
                <li class="item"><a href="#list8" data-toggle="tab">门头沟</a></li>
            </ul>
            <?php
            echo \yii\bootstrap\Tabs::widget();
            ?>
            <div class="hospitals">
                <div id="list1" class="tab-pane active">
                    <?php
                    $hospitals = \common\models\UserDoctor::find()->select('name')->where(['county' => 1102])->all();
                    foreach ($hospitals as $k => $v) {
                        ?>
                        <div class="item">
                            <div class="logo">
                                <img src="/img/view_hospital_logo.png" width="93" height="93">
                            </div>
                            <div class="title"><?= $v->name ?></div>
                            <div class="name">社区卫生服务中心</div>
                        </div>

                    <?php } ?>
                </div>
                <div id="list2" class="tab-pane">
                    <?php
                    $hospitals = \common\models\UserDoctor::find()->select('name')->where(['county' => 1114])->all();
                    foreach ($hospitals as $k => $v) {
                        ?>
                        <div class="item">
                            <div class="logo">
                                <img src="/img/view_hospital_logo.png" width="93" height="93">
                            </div>
                            <div class="title"><?= $v->name ?></div>
                            <div class="name">社区卫生服务中心</div>
                        </div>

                    <?php } ?>
                </div>
                <div id="list3" class="tab-pane">
                    <?php
                    $hospitals = \common\models\UserDoctor::find()->select('name')->where(['county' => 1108])->all();
                    foreach ($hospitals as $k => $v) {
                        ?>
                        <div class="item">
                            <div class="logo">
                                <img src="/img/view_hospital_logo.png" width="93" height="93">
                            </div>
                            <div class="title"><?= $v->name ?></div>
                            <div class="name">社区卫生服务中心</div>
                        </div>

                    <?php } ?>
                </div>
                <div id="list4" class="tab-pane">
                    <?php
                    $hospitals = \common\models\UserDoctor::find()->select('name')->where(['county' => 1105])->all();
                    foreach ($hospitals as $k => $v) {
                        ?>
                        <div class="item">
                            <div class="logo">
                                <img src="/img/view_hospital_logo.png" width="93" height="93">
                            </div>
                            <div class="title"><?= $v->name ?></div>
                            <div class="name">社区卫生服务中心</div>
                        </div>

                    <?php } ?>
                </div>
                <div id="list5" class="tab-pane">
                    <?php
                    $hospitals = \common\models\UserDoctor::find()->select('name')->where(['county' => 1101])->all();
                    foreach ($hospitals as $k => $v) {
                        ?>
                        <div class="item">
                            <div class="logo">
                                <img src="/img/view_hospital_logo.png" width="93" height="93">
                            </div>
                            <div class="title"><?= $v->name ?></div>
                            <div class="name">社区卫生服务中心</div>
                        </div>

                    <?php } ?>            </div>
                <div id="list6" class="tab-pane">
                    <?php
                    $hospitals = \common\models\UserDoctor::find()->select('name')->where(['county' => 1107])->all();
                    foreach ($hospitals as $k => $v) {
                        ?>
                        <div class="item">
                            <div class="logo">
                                <img src="/img/view_hospital_logo.png" width="93" height="93">
                            </div>
                            <div class="title"><?= $v->name ?></div>
                            <div class="name">社区卫生服务中心</div>
                        </div>

                    <?php } ?>             </div>
                <div id="list7" class="tab-pane">
                    <?php
                    $hospitals = \common\models\UserDoctor::find()->select('name')->where(['county' => 1111])->all();
                    foreach ($hospitals as $k => $v) {
                        ?>
                        <div class="item">
                            <div class="logo">
                                <img src="/img/view_hospital_logo.png" width="93" height="93">
                            </div>
                            <div class="title"><?= $v->name ?></div>
                            <div class="name">社区卫生服务中心</div>
                        </div>

                    <?php } ?>             </div>
                <div id="list8" class="tab-pane">
                    <?php
                    $hospitals = \common\models\UserDoctor::find()->select('name')->where(['county' => 1109])->all();
                    foreach ($hospitals as $k => $v) {
                        ?>
                        <div class="item">
                            <div class="logo">
                                <img src="/img/view_hospital_logo.png" width="93" height="93">
                            </div>
                            <div class="title"><?= $v->name ?></div>
                            <div class="name">社区卫生服务中心</div>
                        </div>

                    <?php } ?>               </div>
            </div>
        </div>
    </div>
<?php
Modal::begin([
    'id' => 'map-modal',
    'header' => '<h4 class="modal-title">合作单位</h4>',
    'size'=>Modal::SIZE_LARGE,
]);
?>
    <div id="container" style="height: 500px; width: 100%;"></div>
<?php
Modal::end();
?>
<?php
$hospitals=\common\models\UserDoctor::find()->select('name,longitude,latitude')->where(['city'=>11])->andWhere(['>','longitude',0])->andWhere(['>','latitude',0])->asArray()->all();

$hospitalsJson=json_encode($hospitals);
$updateJs = <<<JS
var hospitals={$hospitalsJson};
$("#map-modal").on('show.bs.modal', function (e) {
    var height=$(window).height();
    $('#container').height(height*0.8);
    var center = new qq.maps.LatLng(39.907366,116.397743);
    var map = new qq.maps.Map(document.getElementById('container'),{
        center: center,
        zoom: 11
    });
    $.each(hospitals,function(name,value) {
        var center = new qq.maps.LatLng(value.latitude,value.longitude);
        var label = new qq.maps.Label({
        style:{color:"#ffffff",backgroundColor:"#000000"},
            position: center,
            map: map,
            content:value.name
        });
    });
    
});
JS;
$this->registerJs($updateJs);
?>