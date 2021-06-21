<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/3/3
 * Time: 下午12:06
 */
use yii\widgets\Pjax;
$userDoctor=\common\models\UserDoctor::findOne(['userid'=>$doctorid]);
$hospital=\common\models\Hospital::findOne($userDoctor->hospitalid);
$this->title="就诊列表";
?>
<style>
    .item{text-align:center;display: flex;justify-content: space-between;margin: 15px;height: 150px;line-height: 150px;font-size: 40px;background: #039A85;padding-left: 64px;padding-right:42px; }
    .text{color: #ffffff; width: 25%;}
    .num{color:#FFF000;}
</style>
<div style="width: 100vw;height: 100vh;background-color:#098876;">
    <div style="height: 100vh;height: 83px;background: linear-gradient(180deg, #098876 0%, #07AA93 100%);display:flex;justify-content: space-between;line-height: 83px;color: #FFFFFF;font-size: 30px;padding-right: 20px;">
        <div style="padding-left:20px;padding-right:40px;height: 83px;background: linear-gradient(180deg, #D8FFFA 0%, #FFFFFF 100%);border-radius: 0px 42px 42px 0px;line-height: 83px;font-size: 36px;color: #039A85;"><?=$hospital->name?></div>
        <div id="start" onclick="getList();">点击开始</div>
    </div>
    <?php Pjax::begin([
        'id' => 'countries',
    ]) ?>
    <div class="item" data-level="0" data="" style="height: 100px; line-height: 100px; font-size: 30px;">
        <div class="num text">序号</div>
        <div class="text">姓名</div>
        <div class="text">诊室</div>
        <div class="text">时间段</div>
    </div>
    <?php
    $i=0;
        foreach($list as $k=>$v){
            if($v){
            foreach($v as $vk=>$vv){
                $i++;
                if($i>3){ $i=0; break;}
                $appointCallingListModel = \common\models\AppointCallingList::findOne($vv);
                if(!$appointCallingListModel) continue;
                if(!$appointCallingListModel->aid) {
                    $name="临时";
                }else {
                    $appoint = \common\models\Appoint::findOne($appointCallingListModel->aid);

                    $name=$appoint->name();
                }
                if($appointCallingListModel->acid){
                    $appointCalling=\common\models\AppointCalling::findOne($appointCallingListModel->acid);
                    $zname= $appointCalling->name;
                }else{
                    $zname= "待定";
                }
                $num=$appointCallingListModel->time.\common\models\AppointCallingList::listName($appointCallingListModel->id,$appointCallingListModel->doctorid, $appointCallingListModel->type,$appointCallingListModel->time);
    ?>
                <div class="item" data-level="<?=$appointCallingListModel->acid&&$appointCallingListModel->calling?1:0?>" data-id="<?=$appointCallingListModel->id?>" data="请<?=$num?>号 <?=!$appointCallingListModel->aid?'':$name?> 到<?=$zname?>就诊___请<?=$num?>号 <?=$name?> 到<?=$zname?>就诊">
                    <div class="num text"><?=$num?>号</div>
                    <div class="text"><?=$name?></div>
                    <div class="text"><?=$zname?></div>
                    <div class="text"><?=$appointCallingListModel->time?\common\models\Appoint::$timeText[$appointCallingListModel->time]:'临时'?></div>
                </div>
            <?php }}}?>

    <?php

    $this->registerJs(

        '
    $("document").ready(function(){ 
        setTimeout(function testFunction(){
            $.pjax.reload({container:"#countries", async: false});
        },"2000");
    });'
    );
    ?>
    <?php Pjax::end(); ?>

    <div id="test" style="display: none;">
        <audio id="myAudio" controls><source src="" id="myAu" type="audio/mpeg">您的浏览器不支持 audio 元素。</audio>

    </div>
</div>
<script>
    var list=[];
    function getList(){
        document.getElementById("start").innerHTML='就诊队列';

        list=[];
        var eachcount= 0;
        var issa=0;
        jQuery(".item").each(function(){
            var level=$(this).attr('data-level');
            if(level==1){
                issa=1;
                var text=$(this).attr('data');
                var id=$(this).attr('data-id');
                jQuery.get('http://hospital.child.wedoctors.com.cn/appoint-calling/ttl?text='+text+'&id='+id,function (e) {
                    eachcount++
                    list.push(e.src);
                    if(eachcount>=$(".item").length){
                        console.log('i'+eachcount);
                        console.log('list'+$(".item").length);
                        actionList();
                    }
                })
            }else{
                eachcount++
            }

        });
        if(!issa){
            console.log(123);
            setTimeout('getList()',4000);
        }
    };
    function actionList(){
        console.log(list);
        if(list.length>0){
            var source = document.getElementById("myAu");
            source.src=list.pop();
            var vMP3 = document.getElementById("myAudio");
            vMP3.load();
            vMP3.addEventListener('ended', actionList, false);
            vMP3.play();
        }else{
            getList();
        }
    }
</script>
<?php
$updateJs = <<<JS
     getList();
JS;
$this->registerJs($updateJs);
?>