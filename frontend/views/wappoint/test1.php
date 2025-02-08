

<div class="wrapper appoint_list">
    <div class="content-wrapper">
        <style>
            .modal_title text {
                font-weight: bold;
                font-size: 16px
            }
            .modal_body{line-height: 24px;}
            .rad{color: rgba(240,85,70,1); font-size: 14px; margin-top: 10px;}
            .button{
                width:179px;
                height:41px;
                background:rgba(240,85,70,1);
                border-radius:20px;
                text-align: center;
                line-height: 41px;
                color: #ffffff;
                display: block;
                margin: 0 auto;
                margin-top: 20px;
            }
        </style>

        <div class="fo" style="margin-top: 100px">
            <a class="button" href="weixin://dl/business/?appid=<?=\Yii::$app->params['doctor_AppID']?>&path=subpackage/reservation/index" >
                点击进入小程序预约
            </a>
            <a class="button" href="/wappoint/my" >
                查看历史预约记录
            </a>
        </div>
    </div>
</div>