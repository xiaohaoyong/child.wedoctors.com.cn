<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/10/29
 * Time: 上午11:14
 */
frontend\assets\SignAsset::register($this);
?>
    <style>
        body {
            background: #F3F3F3;
        }

        #signature {
            width: 100vw;
            height: 100vh;
        }
        .health-records-sign .left-title {
            position: absolute;
            right: -60px;
            top: 120px;
            transform: rotate(90deg);
        }
    </style>
    <div class="health-records-sign">
        <div id="signature" style="padding: 10px 10px;"></div>
        <div class="right-button">
            <div class="health-records-button">
                <button id="save">确认</button>
            </div>
            <div class="health-records-txt">请于白色区域签字确认</div>
        </div>
        <div class="left-title">
            签字即代表以上流调内容属实
        </div>
    </div>
<?php
$updateJs = <<<JS
    var width=$(window).width()-20;
    var height=$(window).height()-25;
    $(document).ready(function() {
        $("#signature").jSignature('init',{height:'100%',width:'100%','background-color':'#fff'})
    })

    $('#save').click(function () {
        if( $("#signature").jSignature('getData', 'native').length == 0){
            alert("请先进行签名");
            return;
        }else{
            var datapair = $("#signature").jSignature("getData", "image");
            var i = new Image();
            i.src = "data:" + datapair[0] + "," + datapair[1];
            i.image = datapair[1];
            console.log(i.image);
            $.ajax({
                url: "/question-naire/save?id={$id}",
                //dataType: "json",
                contentType: "application/json; charset=utf-8",
                data: "{\"image_data\":\"" + encodeURIComponent(i.image) + "\"}",//避免base64长度过大，json传输
                type: "post",
                cache: false,
                success: function (msg) {
                    window.location.href="/question-naire/healthy?id="+msg.data;
                }
            });
        }
    });
    
JS;
$this->registerJs($updateJs);

?>