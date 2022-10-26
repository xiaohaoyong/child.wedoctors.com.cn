<!--pages/appoint/my.wxml-->
<?php
$this->title="我的成人预约";
?>
<div class="my">
    <div class="header">
        <a href="/wappoint/my?type=6" class="item <?=$type==6?'on':''?>" >待确认</a>
        <a href="/wappoint/my?type=1" class="item <?=$type==1?'on':''?>" >进行中</a>
        <a href="/wappoint/my?type=2" class="item <?=$type==2?'on':''?>" >已完成</a>
        <a href="/wappoint/my?type=3" class="item <?=$type==3?'on':''?>" >已取消</a>
        <a href="/wappoint/my?type=4" class="item <?=$type==4?'on':''?>" >已过期</a>
    </div>

    <div class="content">
        <?php
        if($list){
        foreach ($list as $k=>$v){?>
        <div class="myrow">
            <div class="title">
                <div class="tag on<?=$v['state']?>"><?=$v['stateText']?></div>
                <div class="button">
                    <?php if($v['state']==1 || $v['state']==5 || $v['state']==6){?>
                        <a href="/wappoint/state?id=<?=$v['id']?>&type=1" class="cancel">取消预约</a>

                    <?php } if($v['state']==5){?>
                    <div class="confirm">
                        <form bindsubmit="confirm" report-submit="true" id="<?=$v['id']?>tr">
                            <input type="text" name="id" value="<?=$v['id']?>" hidden="true"/>
                            <input type="text" name="k" value="<?=$v['id']?>" hidden="true"/>
                            <input type="text" name="type" value="2" hidden="true"/>
                            <button formType="submit">确认预约</button>
                        </form>
                    </div>
                    <?php }?>
                </div>
            </div>
            <div>
                <?php if($type==1){?><a href="/wappoint/view?id=<?=$v['id']?>"> <?php }?>
                <div class="name"><?=$v['child_name']?></div>
                <div class="mylist">
                    <div class="item">
                        <div class="title1">预约项:</div>
                        <div class="value"><?=$v['type']?></div>
                    </div>
                    <div class="item">
                        <div class="title1">预约社区:</div>
                        <div class="value"><?=$v['hospital']?></div>
                    </div>
                    <div class="item">
                        <div class="title1">预约时间:</div>
                        <div class="value"><?=$v['time']?></div>
                    </div>
                    <?php if($type==2 || $type==4){?>
                    <div class="item">
                        <div class="title1">预约疫苗:</div>
                        <div class="value"><?=$v['vaccineStr']?></div>
                    </div>
                    <?php }?>
                </div>
                    <?php if($type==1){?></a><?php }?>
            </div>
        </div>
        <?php }
        }else{echo $userid;}?>
    </div>

</div>