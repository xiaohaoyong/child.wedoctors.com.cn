<!--pages/appoint/my.wxml-->
<?php
$this->title="我的HPV预约";
?>
<div class="my">
    <div class="content">
        <?php foreach ($model as $k=>$v){?>
        <div class="myrow">
            <div class="title">
                <div class="tag"><?=\common\models\AppointHpv::$stateText[$v->state]?></div>
            </div>
            <div>
                <a href="/appoint-hpv/view?id=<?=$v->id?>">
                <div class="name">预约人：<?=$v->name?></div>
                <div class="mylist">
                    <div class="item">
                        <div class="title1">预约项:</div>
                        <div class="value"><?=\common\models\Vaccine::findOne($v->vid)->name?></div>
                    </div>
                    <div class="item">
                        <div class="title1">预约社区:</div>
                        <div class="value"><?=\common\models\Hospital::findOne(['id'=>\common\models\UserDoctor::findOne(['userid'=>$v->doctorid])->hospitalid])->name?></div>
                    </div>
                    <div class="item">
                        <div class="title1">预约时间:</div>
                        <div class="value"><?=!$v->state?'待定':$v->date?></div>
                    </div>
                </div>
                    </a>
            </div>
        </div>
        <?php }?>
    </div>

</div>