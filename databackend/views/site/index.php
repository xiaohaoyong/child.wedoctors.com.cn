<?php

/* @var $this yii\web\View */

$this->title = '首页';
databackend\assets\IndexAsset::register($this);

?>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
            <span class="info-box-icon" style="height: 128px; line-height: 128px;"><i class="fa fa-heart-o"></i></span>

            <div class="info-box-content" style="height: 128px; padding-top: 10px">
                <span class="info-box-text" style="height: 30px; line-height: 30px">管理儿童总数：<?=$data['childNum']?></span>
                <span class="info-box-number" style="height: 30px; line-height: 30px"><?=$data['todayNum']?>/<?=$data['todayNumTotal']?></span>

                <div class="progress">
                    <div class="progress-bar" style="width: <?=$data['baifen']?>%"></div>
                </div>
                <span class="progress-description" style="height: 30px; line-height: 30px">
                    <?=$data['baifen']?>% 今日签约/已完成签约
                  </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?=$data['todayNumTotal']?></h3>
                <p>已完成签约管理儿童数</p>
            </div>
            <div class="icon">
                <i class="fa fa-bar-chart"></i>
            </div>
            <a href="<?=\yii\helpers\Url::to(['child-info/index'])?>" class="small-box-footer">点击查看<i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?=$data['childNum']?></h3>
                <p>辖区内管理儿童数</p>
            </div>
            <div class="icon">
                <i class="fa fa-user-plus"></i>
            </div>
            <a href="<?=\yii\helpers\Url::to(['child-info/index'])?>" class="small-box-footer">点击查看<i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?=$data['articleNum']?></h3>
                <p style="font-size: 14px; margin-bottom: 5px; line-height: 14px;">宣教总次数</p>
                <p style="font-size: 12px; margin-bottom: 0px; line-height: 12px;">规范化管理率：100%</p>
            </div>
            <div class="icon">
                <i class="fa fa-pie-chart"></i>
            </div>
            <a href="<?=\yii\helpers\Url::to(['user-doctor/index'])?>" class="small-box-footer">点击查看<i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- /.col -->
</div>
<div class="row">
    <section class="col-lg-5 connectedSortable ui-sortable">
        <!-- solid sales graph -->
        <div class="box box-solid bg-teal-gradient">
            <div class="box-header">
                <i class="fa fa-th"></i>

                <h3 class="box-title">每日数据状况</h3>
            </div>
            <div class="box-body border-radius-none">
                <div class="chart" id="line-chart" style="height: 250px;"></div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-border">
                <div class="row" style="height: 50px; line-height: 50px; color: #000;">
                    <div class="col-xs-6 text-center">
                        今日已宣教儿童数：
                    </div>
                    <!-- ./col -->
                    <div class="col-xs-6 text-center">
                        今日未宣教儿童数：
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->
    </section>
    <div class="col-lg-7">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">社区服务中心签约管理数据</h3>
                <div class="box-tools">
                    <a href="<?=\yii\helpers\Url::to(['user-doctor/index'])?>">查看更多</a>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-condensed">
                    <tbody><tr>
                        <th style="width: 10px">#</th>
                        <th>社区卫生服务中心</th>
                        <th>辖区内管理儿童数</th>
                        <th>今日 / 已签约数</th>
                        <th>今日 / 已宣教数</th>
                        <th>管理服务器率</th>
                        <th style="width: 40px">规范</th>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>Update software</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    </tbody></table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
<div>