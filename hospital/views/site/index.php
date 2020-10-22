<?php

/* @var $this yii\web\View */

$this->title = '首页';
hospital\assets\IndexAsset::register($this);

?>
<style>
    .info-box-content .title {
        font-size: 16px;
        line-height: 24px;
    }

    .info-box-content .item {
        line-height: 24px;
    }
</style>
<div class="col-xs-12">

    <div class="row">
        <?php if (in_array(Yii::$app->user->identity->county, [1105, 1106,1109,1116]) || in_array(Yii::$app->user->identity->hospitalid, [110587,110586, 110582, 110583, 110584, 110589, 110571, 110590, 110591, 110593, 110592, 110594, 110595, 110596, 110597, 110598, 110599, 110602, 110601, 110603, 110604, 110605, 110606, 110607, 110608, 110609, 110610, 110613, 110614, 110615, 110616, 110617, 110618, 110620,110624,110625,110626,110627,110628,110629,110630,110632,110633,110634])) { ?>

            <div class="col-md-3">
                <!-- Widget: user widget style 1 -->
                <div class="box box-solid">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="box-header with-border bg-yellow">
                        <h3 class="box-title">0-3岁中医药儿童健康管理</h3>
                        <!-- /.box-tools -->
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            <li><a href="#">今日签约数 <span class="pull-right badge bg-blue"><?= $data['todayNum'] ?></span></a></li>
                            <li><a href="#">已完成签约 <span class="pull-right badge bg-aqua"><?= $data['todayNumTotal'] ?></span></a></li>
                            <li><a href="#">已完成签约率 <span class="pull-right badge bg-green"><?= $data['baifen'] ?>%</span></a></li>
                            <li><a href="#">管理儿童总数 <span class="pull-right badge bg-red"><?= $data['childNum'] ?></span></a></li>
                        </ul>
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
            <div class="col-md-3">
                <!-- Widget: user widget style 1 -->
                <div class="box box-solid">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="box-header with-border bg-green">
                        <h3 class="box-title">0-6岁家庭医生签约服务</h3>
                        <!-- /.box-tools -->
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            <li><a href="#">今日签约数 <span class="pull-right badge bg-blue"><?= $data['todayInkNum'] ?></span></a></li>
                            <li><a href="#">已完成签约 <span class="pull-right badge bg-aqua"><?= $data['AutoNum'] ?></span></a></li>
                            <li><a href="#">已完成签约率 <span class="pull-right badge bg-green"><?= $data['abaifen'] ?>%</span></a></li>
                            <li><a href="#">管理儿童总数 <span class="pull-right badge bg-red"><?= $data['achildNum'] ?></span></a></li>
                        </ul>
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
            <div class="col-md-3">
                <!-- Widget: user widget style 1 -->
                <div class="box box-solid">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="box-header with-border bg-aqua">
                        <h3 class="box-title">孕产期管理</h3>
                        <!-- /.box-tools -->
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            <li><a href="#">今日签约数 <span class="pull-right badge bg-blue"><?= $data['todayPregLCount'] ?></span></a></li>
                            <li><a href="#">已完成签约 <span class="pull-right badge bg-aqua"><?= $data['pregLCount'] ?></span></a></li>
                            <li><a href="#">已完成签约率 <span class="pull-right badge bg-green"><?= $data['pregCount'] ? round(($data['pregLCount'] / $data['pregCount']) * 100, 1) : 0 ?>%</span></a></li>
                            <li><a href="#">管理孕产妇总数 <span class="pull-right badge bg-red"><?= $data['pregCount'] ?></span></a></li>
                        </ul>
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
            <div class="col-md-3">
                <!-- Widget: user widget style 1 -->
                <div class="box box-solid">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="box-header with-border bg-red">
                        <h3 class="box-title">宣教</h3>
                        <!-- /.box-tools -->
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            <li><a href="#">宣教总次数 <span class="pull-right badge bg-blue"><?= $data['articleNum'] ?></span></a></li>
                            <li><a href="#">规范化管理率 <span class="pull-right badge bg-aqua">100%</span></a></li>
                            <li><a href="#"></a></li>
                            <li><a href="#"></a></li>
                        </ul>
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
        <?php } else { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green">
                    <div class="info-box-content" style="margin-left:0px;height: 128px; padding-top: 10px">
                <span class="info-box-text"
                      style="height: 30px; line-height: 30px">管理儿童总数：<?= $data['childNum'] ?></span>
                        <span class="info-box-number"
                              style="height: 30px; line-height: 30px"><?= $data['todayNumTotal'] ?>
                            /<?= $data['childNum'] ?>&nbsp;&nbsp;&nbsp;<?= $data['baifen'] ?>% </span>

                        <div class="progress">
                            <div class="progress-bar" style="width: <?= $data['baifen'] ?>%"></div>
                        </div>
                        <span class="progress-description" style="height: 30px; line-height: 30px">
                    已完成签约/管理儿童总数
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?= $data['todayNum'] ?></h3>
                        <p>今日签约</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-plus"></i>
                    </div>
                    <a href="<?= \yii\helpers\Url::to(['child-info/index']) ?>" class="small-box-footer">点击查看<i
                                class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- /.col -->

            <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?= $data['todayNumTotal'] ?></h3>
                        <p>已完成签约管理儿童数</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-bar-chart"></i>
                    </div>
                    <a href="<?= \yii\helpers\Url::to(['child-info/index?ChildInfoSearchModel[level]=1']) ?>"
                       class="small-box-footer">点击查看<i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- /.col -->

            <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?= $data['articleNum'] ?></h3>
                        <p style="font-size: 14px; margin-bottom: 5px; line-height: 14px;">宣教总次数</p>
                        <p style="font-size: 12px; margin-bottom: 0px; line-height: 12px;">规范化管理率：100%</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-pie-chart"></i>
                    </div>
                    <a href="<?= \yii\helpers\Url::to(['user-doctor/index']) ?>" class="small-box-footer">点击查看<i
                                class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- /.col -->
        <?php } ?>

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
                    <div class="row" style="height: 53px; line-height: 53px; color: #000;">
                        <div class="col-xs-6 text-center">
                            今日已宣教儿童数：<?= $data['articleNumToday'] ?>
                        </div>
                        <!-- ./col -->
                        <div class="col-xs-6 text-center">
                            今日未宣教儿童数：<?= $data['articleNoMonth'] > 0 ? $data['articleNoMonth'] : 0 ?>
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
                    <h3 class="box-title">最近签约儿童</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover" style="font-size: 12px;">
                        <tr>
                            <th>姓名</th>
                            <th>年龄</th>
                            <th>签约团队</th>
                            <th>父母</th>
                            <th>签约时间</th>
                            <th>联系电话</th>
                        </tr>
                        <?php foreach ($now as $k => $v) {

                            $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $v->birthday));
                            if ($DiffDate[0]) {
                                $age = $DiffDate[0] . "岁";
                            } elseif ($DiffDate[1]) {
                                $age = $DiffDate[1] . "月";
                            } else {
                                $age = $DiffDate[2] . "天";
                            }
                            ?>
                            <tr>
                                <td><?= $v->name ?></td>
                                <td><?= $age ?></td>
                                <td><?= $v->doctor[0]->name ?></td>
                                <td><?= $v->parent->father . "/" . $v->parent->mother ?></td>
                                <td><?= date('Y-m-d', \common\models\DoctorParent::findOne(['parentid' => $v->userid])->createtime) ?></td>
                                <td><?= $v->parent->mother_phone ? $v->parent->mother_phone : $v->parent->father_phone ? $v->parent->father_phone : $v->parent->field12 ? $v->parent->field12 : $v->user->phone ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>

    </div>
    <script>
        var line_data =<?=json_encode($line_data)?>;


    </script>
</div>