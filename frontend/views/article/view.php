<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />

    <title><?=$article['info']['title']?></title>
    <link href="http://static.c.wedoctors.com.cn/css.css?t=14323" rel="stylesheet">    <script src="http://static.j.wedoctors.com.cn/js/flexible.js"></script>
    <style>
        .article_Com video{width: 100%}
    </style>
</head>
<body>
<article class="article-box clearfix">
    <h2 class="Infor_Th f20"><?=$article['info']['title']?></h2>


    <div class="box Infor_line">
        <div class="box-flex">
            <div class="box">
                <div class="box-flex article_txt article_topTxt f12" style="padding: 0px 0.4rem;">
                    <span class="f15"><?=$article['createtime']?> <?=$article['info']['source']?> </span>
                </div>
            </div>
        </div>

    </div>
    <div class="article_Com clearfix f16 deepgray">
        <?=$article['info']['content']?>
    </div>

    <div class="mlr15 article_comarea_th clearfix f16" id="comList">

        热门评论<span>（<?=$comment['total']?>）</span>
    </div>

    <div class="mlr15 article_comList clearfix">
        <?php
        if($comment['list']){
            foreach($comment['list'] as $key=>$value){
                ?>
                <div class="box clearfix article_one">
                    <div class="article_photo">
                        <img src="<?=$value['user']['img']?$value['user']['img'] : 'data:image/jpeg;base64,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AAAAAjhCSU0D9QAAAAAASAAvZmYAAQBsZmYABgAAAAAAAQAvZmYAAQChmZoABgAAAAAAAQAyAAAAAQBaAAAABgAAAAAAAQA1AAAAAQAtAAAABgAAAAAAAThCSU0D+AAAAAAAcAAA/////////////////////////////wPoAAAAAP////////////////////////////8D6AAAAAD/////////////////////////////A+gAAAAA/////////////////////////////wPoAAA4QklNBAAAAAAAAAIAADhCSU0EAgAAAAAABAAAAAA4QklNBDAAAAAAAAIBAThCSU0ELQAAAAAABgABAAAAAThCSU0ECAAAAAAAEAAAAAEAAAJAAAACQAAAAAA4QklNBB4AAAAAAAQAAAAAOEJJTQQaAAAAAAM/AAAABgAAAAAAAAAAAAAAZAAAAGQAAAAFZypoB5iYAC0AMgAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAAZAAAAGQAAAAAAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAQAAAAAQAAAAAAAG51bGwAAAACAAAABmJvdW5kc09iamMAAAABAAAAAAAAUmN0MQAAAAQAAAAAVG9wIGxvbmcAAAAAAAAAAExlZnRsb25nAAAAAAAAAABCdG9tbG9uZwAAAGQAAAAAUmdodGxvbmcAAABkAAAABnNsaWNlc1ZsTHMAAAABT2JqYwAAAAEAAAAAAAVzbGljZQAAABIAAAAHc2xpY2VJRGxvbmcAAAAAAAAAB2dyb3VwSURsb25nAAAAAAAAAAZvcmlnaW5lbnVtAAAADEVTbGljZU9yaWdpbgAAAA1hdXRvR2VuZXJhdGVkAAAAAFR5cGVlbnVtAAAACkVTbGljZVR5cGUAAAAASW1nIAAAAAZib3VuZHNPYmpjAAAAAQAAAAAAAFJjdDEAAAAEAAAAAFRvcCBsb25nAAAAAAAAAABMZWZ0bG9uZwAAAAAAAAAAQnRvbWxvbmcAAABkAAAAAFJnaHRsb25nAAAAZAAAAAN1cmxURVhUAAAAAQAAAAAAAG51bGxURVhUAAAAAQAAAAAAAE1zZ2VURVhUAAAAAQAAAAAABmFsdFRhZ1RFWFQAAAABAAAAAAAOY2VsbFRleHRJc0hU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cAAAABcAQQBkAG8AYgBlACAAUABoAG8AdABvAHMAaABvAHAAIABDAEMAIAAyADAAMQA4AAAAAQA4QklNBAYAAAAAAAcACAAAAAEBAP/hDqhodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQwIDc5LjE2MDQ1MSwgMjAxNy8wNS8wNi0wMTowODoyMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTggKE1hY2ludG9zaCkiIHhtcDpDcmVhdGVEYXRlPSIyMDE4LTA1LTE4VDEzOjEwOjU2KzA4OjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDE4LTA1LTE4VDEzOjEwOjU2KzA4OjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAxOC0wNS0xOFQxMzoxMDo1NiswODowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo0ZmExMGEzMS1jNWQ2LTQxOGEtYWExZC0wZDZkYjhkMGQ5MTkiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDo2NWI0ZTI1ZS0xMDc4LTgyNGMtOTYwMi0wMjdiZGYwNGRiMDAiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo1YTE0NzdlNy04YTJiLTQ1MTItOTc3Mi1kOTFlNmM4MzMzMmQiIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgZGM6Zm9ybWF0PSJpbWFnZS9qcGVnIj4gPHhtcE1NOkhpc3Rvcnk+IDxyZGY6U2VxPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0iY3JlYXRlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo1YTE0NzdlNy04YTJiLTQ1MTItOTc3Mi1kOTFlNmM4MzMzMmQiIHN0RXZ0OndoZW49IjIwMTgtMDUtMThUMTM6MTA6NTYrMDg6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE4IChNYWNpbnRvc2gpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo0ZmExMGEzMS1jNWQ2LTQxOGEtYWExZC0wZDZkYjhkMGQ5MTkiIHN0RXZ0OndoZW49IjIwMTgtMDUtMThUMTM6MTA6NTYrMDg6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE4IChNYWNpbnRvc2gpIiBzdEV2dDpjaGFuZ2VkPSIvIi8+IDwvcmRmOlNlcT4gPC94bXBNTTpIaXN0b3J5PiA8cGhvdG9zaG9wOkRvY3VtZW50QW5jZXN0b3JzPiA8cmRmOkJhZz4gPHJkZjpsaT5hZG9iZTpkb2NpZDpwaG90b3Nob3A6MjFhZGNiMzktNTlhNC0xMWU4LWFjM2EtYjQ3MDA5MWIwMjYwPC9yZGY6bGk+IDwvcmRmOkJhZz4gPC9waG90b3Nob3A6RG9jdW1lbnRBbmNlc3RvcnM+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDw/eHBhY2tldCBlbmQ9InciPz7/4gxYSUNDX1BST0ZJTEUAAQEAAAxITGlubwIQAABtbnRyUkdCIFhZWiAHzgACAAkABgAxAABhY3NwTVNGVAAAAABJRUMgc1JHQgAAAAAAAAAAAAAAAAAA9tYAAQAAAADTLUhQICAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABFjcHJ0AAABUAAAADNkZXNjAAABhAAAAGx3dHB0AAAB8AAAABRia3B0AAACBAAAABRyWFlaAAACGAAAABRnWFlaAAACLAAAABRiWFlaAAACQAAAABRkbW5kAAACVAAAAHBkbWRkAAACxAAAAIh2dWVkAAADTAAAAIZ2aWV3AAAD1AAAACRsdW1pAAAD+AAAABRtZWFzAAAEDAAAACR0ZWNoAAAEMAAAAAxyVFJDAAAEPAAACAxnVFJDAAAEPAAACAxiVFJDAAAEPAAACAx0ZXh0AAAAAENvcHlyaWdodCAoYykgMTk5OCBIZXdsZXR0LVBhY2thcmQgQ29tcGFueQAAZGVzYwAAAAAAAAASc1JHQiBJRUM2MTk2Ni0yLjEAAAAAAAAAAAAAABJzUkdCIElFQzYxOTY2LTIuMQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAWFlaIAAAAAAAAPNRAAEAAAABFsxYWVogAAAAAAAAAAAAAAAAAAAAAFhZWiAAAAAAAABvogAAOPUAAAOQWFlaIAAAAAAAAGKZAAC3hQAAGNpYWVogAAAAAAAAJKAAAA+EAAC2z2Rlc2MAAAAAAAAAFklFQyBodHRwOi8vd3d3LmllYy5jaAAAAAAAAAAAAAAAFklFQyBodHRwOi8v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hcoBUjC1GB3bi5HsAzRwZeTAVCoTf0sjED8n3K1p727NcxiPeNgmU+egpKv7H0H+R6xX3X7lXOO3XDT8o8+Wci17fGWe1kA9C0QnUn5gj7B0/N/Md+LGDVqzbXV+8TkQpKCj2Vs7CuXtez1ozqsgJ+pAb2rPu9yRbVks9kuPG+UMSfz19Fg+6R717kRb7vznt/0nnru7qYU+SeDQ/YSOi+dnfzSuw87T1GP6s2PiNiRTK0a57cNUu6c9ECCBLSY9KejwdJNbkeUVqg/g/X2E9697d2ukeHZNtjtVP43PiP8AaFoEB+3X1KnJv3K+VNsliuudeZJ9zdTUwwL9NCfkzlnmYf6Uwn5jqtPdm79077ztZufee4MtujcNewNXl81WS1tW6rfRDG0h8dNSwg2jhiVIoxwqge4cvr+93O6kvdxu5J7t+LOan7PkB5AUA8h1mBsew7Jyztlvs3L21QWW1RDtiiQIo9SaZZj+J2Jdjksek77SdG/Xvfuvde9+691737r3X//SO3758dfQB1737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691//0zt++fHX0Ade9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvdf/9k='?>" alt="">
                    </div>
                    <div class="box-flex">
                        <div class="article_ont box f12 ">
                            <div class="box-flex">
                                <p><span class="f15 article_comname"><?=$value['user']['name']?></span><?=$value['createtime']?></p>
                            </div>
                        </div>
                        <div class="article_comtxt f16 bort"><a><?=$value['content']?></a></div>
                    </div>
                </div>
            <?php }}?>
    </div>

</article>

<script src="http://static.j.wedoctors.com.cn/js/zepto.min.js"></script>
<script src="http://static.j.wedoctors.com.cn/js/article.js?t=070401"></script></body>
</html>
