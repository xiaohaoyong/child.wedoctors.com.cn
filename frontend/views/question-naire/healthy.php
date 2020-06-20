<style>
    .content{padding-top: 30px;}
.header{padding:0 70px;display:flex;justify-content:space-between;align-items:center;margin:0 auto;width:354px;height:90px;background: url("/img/touying.png") no-repeat;background-size: 354px 97px;}

    .zhuangtai{display: flex;flex-direction: column;justify-content: center;align-items: center; margin-top: 40px;}
    body{background-color: #ffffff}
    .view{background:url("/img/qn_view.png") no-repeat;background-size:232px 56px;width: 232px;height: 56px;line-height: 56px; text-align: center;font-size: 18px;color: #ffffff;margin-top: 80px;}
</style>
<div class="content" >

    <div class="header">
        <div class="img"><img src="/img/qn_header.png" width="44"></div>
        <div class="title"style="padding-right: 40px; font-size: 16px;">调查结果显示</div>
    </div>
    <div class="zhuangtai">
        <img src="/img/zhuangtai_<?php echo $is_healthy?'true':'false'?>.png" width="65">
        <div class="value" style="font-size: 30px; margin-top: 10px;"><?php echo $is_healthy?'正常':'异常'?></div>
        <div class="info" style="text-align: center;padding: 0 50px;margin-top: 20px;color: #999999;font-size: 16px;"><?php echo $is_healthy?'前往社区请出示此页':'请您前往就近二级以上医疗机构发热门诊筛查，感谢您的配合！'?></div>
        <div class="info" style="text-align: center;padding: 0 50px;margin-top: 20px;color: #999999;font-size: 16px;">有效日期截止至：<?=date('Y年m月d日 H:i',strtotime('+1 day',$qnaa->createtime))?></div>
        <a href="/question-naire/view?id=<?=$id?>" class="view">查看</a>
    </div>

</div>