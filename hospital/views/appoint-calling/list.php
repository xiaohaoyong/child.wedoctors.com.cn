<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/3/3
 * Time: 下午12:06
 */
?>
<div class="item" data-level="0" data="汪振请到第一诊室"></div>
<div class="item" data-level="1" data="CCC请到第一诊室"></div>
<div class="item" data-level="0" data="BBBB请到第一诊室"></div>
<div id="test" style="display: none;"></div>
<?php
$updateJs = <<<JS
setInterval(function(){
    $(".item").each(function(){
        var level=$(this).attr('data-level');
        if(level==1){
            var text=$(this).attr('data');
            $.get('http://hospital.child.wedoctors.com.cn/appoint-calling/ttl?text='+text,function (e) {
                console.log(e.src);
                var audio=' <audio id="myAudio" controls><source src="'+e.src+'" id="myAu" type="audio/mpeg">您的浏览器不支持 audio 元素。</audio>';
                $('#test').html(audio);
                vMP3 = document.getElementById("myAudio");
                vMP3.play();
                setTimeout('vMP3.play()',3500);
            })
        }
    });
},9000)
JS;
$this->registerJs($updateJs);

?>