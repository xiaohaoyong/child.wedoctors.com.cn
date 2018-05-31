
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />

    <title><?=$article->info->title?></title>
    <link href="http://static.c.wedoctors.com.cn/css.css?t=14323" rel="stylesheet">    <script src="http://static.j.wedoctors.com.cn/js/flexible.js"></script>
</head>
<body>
<article class="article-box clearfix">
    <h2 class="Infor_Th f20"><?=$article->info->title?></h2>


    <div class="box Infor_line">
        <div class="box-flex">
            <div class="box">
                <div class="box-flex article_txt article_topTxt f12">
                    <a href="mid://144" class="f15"> <?=$article->info->source?></a>
                    <p class="f10"><?=$article->createtime?></p>
                </div>
            </div>
        </div>

    </div>
    <div class="article_Com clearfix f16 deepgray">
        <?=$article->info->content?>
    </div>

    <div class="mlr15 article_comarea_th clearfix f16" id="comList">

        热门评论<span>（<?=$comment_total?>）</span>
    </div>

    <div class="mlr15 article_comList clearfix">
        <?php
        if($comment){
            foreach(comment_list as $key=>$value){
                ?>
                <div class="box clearfix article_one">
                    <div class="article_photo">
                        <img src="<?=$value['user']['img']?$value['user']['img'] : $headimg?>" alt="">
                    </div>
                    <div class="box-flex">
                        <div class="article_ont box f12 ">
                            <div class="box-flex">
                                <p><span class="f15 article_comname">{{value.user.name}}</span>{{value.createtime}}</p>
                            </div>
                        </div>
                        <div class="article_comtxt f16 bort"><a href="comment://<?=$v->id?>,<?=$v->id?>,<?=$v->userid?>"><?=$v->content?></a></div>
                    </div>
                </div>
            <?php }}?>
    </div>

</article>

<!-- 滚动出现下载start -->
<div id="DownApp" class="none">
    <a href="http://www.wedoctors.com.cn/down/app.html" target="_blank" class="DownFixed box">
        <div class="box-flex Down_logo"></div>
        <div class="Down_btn f14">立即下载</div>
    </a>
</div>
<!-- 滚动出现下载end -->

<!-- 底部下载 -->
<a href="http://www.wedoctors.com.cn/down/app.html" target="_blank" class="clearfix DownBot f18">下载拉手APP</a>
<!-- 底部下载 -->


<script src="http://static.j.wedoctors.com.cn/js/zepto.min.js"></script>
<script src="http://static.j.wedoctors.com.cn/js/article.js?t=070401"></script></body>
</html>
