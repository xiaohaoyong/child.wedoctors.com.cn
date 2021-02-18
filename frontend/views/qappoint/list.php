<?php
$this->title="成人疫苗接种及两癌筛查预约"
?>
<div class="wrapper appoint_list">
    <div class="content-wrapper">
        <div class="search_box">
        </div>
        <div class="list">
            <div class="item" style="padding: 10px; border-radius: 20px; border: 1px solid #999999;">
                <div class="item-content">
                    <div class="hospital">
                        <div class="name">预约核酸检测</div>
                        <div class="address">温馨提示：请预约前仔细查看各社区温馨提示</div>
                    </div>
                </div>
                <div class="item-button">
                    <div class="phone"></div>
                    <?=\yii\helpers\Html::a('在线预约',['wappoint/index'],['class'=>'button'])?>
                </div>
            </div>
            <div class="item" style="padding: 10px; border-radius: 20px; border: 1px solid #999999;">
                <div class="item-content">
                    <div class="hospital">
                        <div class="name">预约成人疫苗接种疫苗</div>
                        <div class="address">温馨提示：各社区接种方式不同，请预约前仔细查看各社区温馨提示或电话咨询预约社区是否可以进行接种</div>
                    </div>
                </div>
                <div class="item-button">
                    <div class="phone"></div>
                    <?=\yii\helpers\Html::a('在线预约',['wappoint/index'],['class'=>'button'])?>
                </div>
            </div>
            <div class="item" style="padding: 10px; border-radius: 20px; border: 1px solid #999999;">
                <div class="item-content">
                    <div class="hospital">
                        <div class="name">预约两癌筛查</div>
                        <div class="address">温馨提示：筛查人群为北京市户籍年满35-64岁妇女，三年筛查一次（如2019年已筛查，下次筛查时间为2022年），必须携带身份证。注：请务必按照自己的预约时间段前来筛查现场，如有疑问请咨询预约社区或联系在线客服</div>
                    </div>
                </div>
                <div class="item-button">
                    <div class="phone"></div>
                    <?=\yii\helpers\Html::a('在线预约',['qappoint/index'],['class'=>'button'])?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
\yii\bootstrap\Modal::begin([
    'id' => 'create-modal',
]);
\yii\bootstrap\Modal::end();
?>