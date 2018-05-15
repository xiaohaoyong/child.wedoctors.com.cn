<?php

/* @var $this yii\web\View */

$this->title = '首页';
?>
<div class="row">
    <div class="col-md-4 col-sm-4">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-cursor font-purple-intense hide"></i>
                    <span class="caption-subject font-yellow-saffron bold uppercase">儿童管理率</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-8" style="min-height: 167px">
                        <table class="table table-hover table-light">
                            <tr>
                                <td>
                                    今日管理总数
                                </td>
                                <td>
                                    <a href="javascript:;" class="primary-link"><?=$data['todayNum']?></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    辖区内儿童数
                                </td>
                                <td>
                                    <a href="javascript:;" class="primary-link"><?=$data['childNum']?></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    管理儿童数
                                </td>
                                <td>
                                    <a href="javascript:;" class="primary-link"><?=$data['todayNumTotal']?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <div class="easy-pie-chart">
                            <div class="number transactions" data-percent="<?=$data['baifen']?>">
											<span>
											+<?=$data['baifen']?> </span>
                                %
                            </div>
                            <span class="title" style="color: #8896a0;vertical-align: middle; padding-right: 9px;">管理率</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-4">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-cursor font-purple-intense hide"></i>
                    <span class="caption-subject font-green bold uppercase">规范化管理率</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-8"  style="min-height: 167px">
                        <table class="table table-hover table-light">
                            <tr>
                                <td>
                                    宣教总次数
                                </td>
                                <td>
                                    <a href="javascript:;" class="primary-link"><?=$data['articleNum']?></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    规范化指导次数
                                </td>
                                <td>
                                    <a href="javascript:;" class="primary-link"><?=$data['articleNumType']?></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    宣教查看次数
                                </td>
                                <td>
                                    <a href="javascript:;" class="primary-link"><?=$data['articleLockNum1']?>+<?=$data['articleLockNum2']?></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    宣教覆盖儿童数
                                </td>
                                <td>
                                    <a href="javascript:;" class="primary-link"><?=$data['articleChildNum']?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <div class="easy-pie-chart">
                            <div class="number visits" data-percent="100">
											<span>100</span>
                                %
                            </div>
                            <span class="title" style="color: #8896a0;vertical-align: middle; padding-right: 9px;">规范化管理率</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-4">
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-cursor font-purple-intense hide"></i>
                    <span class="caption-subject font-green bold uppercase">知晓率/满意度</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-10"  style="min-height: 167px">
                        <table class="table table-hover table-light">
                            <tr>
                                <td>
                                    用户知晓数据
                                </td>
                                <td>
                                    <a href="javascript:;" class="primary-link"></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    用户满意数据
                                </td>
                                <td>
                                    <a href="javascript:;" class="primary-link"></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php
$js = <<<JS
    $('.easy-pie-chart .number.transactions').easyPieChart({
                animate: 1000,
                size: 75,
                lineWidth: 3,
                barColor: '#F8CB00'
            });
$('.easy-pie-chart .number.visits').easyPieChart({
                animate: 1000,
                size: 75,
                lineWidth: 3,
                barColor: '#1bbc9b'
            });
$('.easy-pie-chart .number.visits1').easyPieChart({
                animate: 1000,
                size: 75,
                lineWidth: 3,
                barColor: '#89C4F4'
            });

JS;
$this->registerJs($js);
?>