<style>
    .content{padding-top: 30px;}
.header{display:flex;justify-content:center;align-items:center;margin:0 auto;height:90px;background: url("/img/touying.png") no-repeat center;background-size: 354px 97px;}

    .zhuangtai{display: flex;flex-direction: column;justify-content: center;align-items: center; margin-top: 40px;}
    body{background-color: #ffffff}
    .view{background:url("/img/qn_view.png") no-repeat;background-size:232px 56px;width: 232px;height: 56px;line-height: 56px; text-align: center;font-size: 18px;color: #ffffff;margin-top: 80px;}
</style>
<div class="content" >

    <div class="header">
        <div class="img"><img src="/img/qn_header.png" width="44"></div>
        <div class="title"style="padding-left: 40px; font-size: 16px;">调查结果显示</div>
    </div>
    <div class="zhuangtai">

        <?php
        if($id==3){
        ?>
            <div class="value" style="font-size: 30px; margin-top: 10px;">提交成功</div>
            <div class="info" style="text-align: center;padding: 0 50px;margin-top: 20px;color: #999999;font-size: 16px;"><?=$name?"填表人姓名：".$name:''?></div>
            <div class="info" style="text-align: center;padding: 0 50px;margin-top: 20px;color: #999999;font-size: 16px;">有效日期截止至：<?=date('Y年m月d日 H:i',strtotime('+1 day',$qnaa->createtime))?></div>
            <a href="/question-naire/view?id=<?=$id?>&fid=<?=$qnaa->qnfid?>" class="view">查看</a>
            <div class="info" style="text-align: center;padding: 0 50px;margin-top: 40px;margin-bottom:40px;color: #999999;font-size: 16px;">北京洛奇医学检验实验室报告查询二维码</div>
        <img src="/img/shiyanshi.jpeg" width="200">
        <div class="info" style="text-align: center;padding: 0 50px;margin-top: 20px;color: #999999;font-size: 16px;">如有问题，请咨询客服电话，我们会尽快为您解决。</div>
        <div class="info" style="text-align: center;padding: 0 50px;margin-top: 20px;color: #999999;font-size: 16px;">客服电话：010-80720625-3</div>
            <?php
        }else{
            ?>
            <img src="/img/zhuangtai_true.png" width="65">
            <div class="value" style="font-size: 30px; margin-top: 10px;">提交成功</div>
            <div class="info" style="text-align: center;padding: 0 50px;margin-top: 20px;color: #999999;font-size: 16px;"><?=$name?"填表人姓名：".$name:''?></div>
            <div class="info" style="text-align: center;padding: 0 50px;margin-top: 20px;color: #999999;font-size: 16px;">有效日期截止至：<?=date('Y年m月d日 H:i',strtotime('+1 day',$qnaa->createtime))?></div>
            <a href="/question-naire/view?id=<?=$id?>&fid=<?=$qnaa->qnfid?>" class="view">查看</a>
        <?php
        }
        ?>
    </div>






</div>