<?php

/* @var $this yii\web\View */

$this->title = '首页';
hospital\assets\IndexAsset::register($this);

?>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">

            <div class="info-box-content" style="margin-left:0px;height: 128px; padding-top: 10px">
                <span class="info-box-text" style="height: 30px; line-height: 30px">管理儿童总数：<?=$data['childNum']?></span>
                <span class="info-box-number" style="height: 30px; line-height: 30px"><?=$data['todayNumTotal']?>/<?=$data['childNum']?>&nbsp;&nbsp;&nbsp;<?=$data['baifen']?>% </span>

                <div class="progress">
                    <div class="progress-bar" style="width: <?=$data['baifen']?>%"></div>
                </div>
                <span class="progress-description" style="height: 30px; line-height: 30px">
                    已完成签约/管理儿童总数
                  </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
<div class="col-md-3 col-sm-6 col-xs-12">
    <!-- small box -->
    <div class="small-box bg-yellow">
        <div class="inner">
            <h3><?=$data['todayNum']?></h3>
            <p>今日签约</p>
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
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?=$data['todayNumTotal']?></h3>
                <p>已完成签约管理儿童数</p>
            </div>
            <div class="icon">
                <i class="fa fa-bar-chart"></i>
            </div>
            <a href="<?=\yii\helpers\Url::to(['child-info/index?ChildInfoSearchModel[level]=1'])?>" class="small-box-footer">点击查看<i class="fa fa-arrow-circle-right"></i>
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
                <div class="row" style="height: 53px; line-height: 53px; color: #000;">
                    <div class="col-xs-6 text-center">
                        今日已宣教儿童数：<?=$data['articleNumToday']?>
                    </div>
                    <!-- ./col -->
                    <div class="col-xs-6 text-center">
                        本月未宣教儿童数：<?=$data['articleNoMonth']>0?$data['articleNoMonth']:0?>
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
                    <?php foreach($now as $k=>$v){

                        $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $v->birthday));
                        if($DiffDate[0]) {
                            $age=$DiffDate[0]."岁";
                        }elseif($DiffDate[1]){
                            $age=$DiffDate[1]."月";
                        }else{
                            $age=$DiffDate[2]."天";
                        }
                        ?>
                    <tr>
                        <td><?=$v->name?></td>
                        <td><?=$age?></td>
                        <td><?=$v->doctor[0]->name?></td>
                        <td><?=$v->parent->father."/".$v->parent->mother?></td>
                        <td><?=date('Y-m-d',\common\models\DoctorParent::findOne(['parentid'=>$v->userid])->createtime)?></td>
                        <td><?=$v->parent->mother_phone?$v->parent->mother_phone:$v->parent->father_phone?$v->parent->father_phone:$v->parent->field12?$v->parent->field12:$v->user->phone?></td>
                    </tr>
                    <?php }?>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>

</div>
<div>
    <script>
        var line_data=<?=json_encode($line_data)?>;



    </script>