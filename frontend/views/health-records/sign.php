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
    </style>
    <div id="signature" style="padding: 10px;"></div>
    <div class="health-records-button">
        <button id="save">确认</button>
    </div>
    <div class="health-records-txt">请于上方区域签字确认</div>
<?php
$updateJs = <<<JS
    var width=$(window).width()-20;
    $(document).ready(function() {
        $("#signature").jSignature({
                'width':width ,
                'height': 400,
                'background-color':'#fff'
        })
    })
    $('#save').click(function () {
            //标准格式但是base64会被tp框架过滤，所不校验，但是jSignature默认是使用png
            var datapair = $("#signature").jSignature("getData", "image");
            var i = new Image();
            i.src = "data:" + datapair[0] + "," + datapair[1];
            i.image = datapair[1];
            console.log(i.image);
            $.ajax({
                url: "/health-records/save",
                //dataType: "json",
                contentType: "application/json; charset=utf-8",
                data: "{\"image_data\":\"" + encodeURIComponent(i.image) + "\"}",//避免base64长度过大，json传输
                type: "post",
                cache: false,
                success: function (msg) {
                               window.location.href="/health-records/done"; 

                }
            });
        });
    
JS;
$this->registerJs($updateJs);

?>