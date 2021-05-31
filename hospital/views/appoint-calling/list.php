 <?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/3/3
 * Time: 下午12:06
 */
?>
 <div data="汪振请到第一诊室"></div>
 <div data="CCC请到第一诊室"></div>
 <div data="BBBB请到第一诊室"></div>
<div id="test"></div>
 <button id="kaiqi" onclick="f()">开启</button>
<script>
    function f() {
        vMP3 = document.getElementById("myAudio");
        vMP3.play();
    }
</script>
 <?php


 $updateJs = <<<JS
   $.get('http://hospital.child.wedoctors.com.cn/appoint-calling/ttl?text=是点击老师点击',function (e) {
       console.log(e.src);
       var audio=' <audio id="myAudio" controls><source src="'+e.src+'" id="myAu" type="audio/mpeg">您的浏览器不支持 audio 元素。</audio>';
       $('#test').html(audio);
       vMP3 = document.getElementById("myAudio");
       vMP3.play();
    })
JS;
 $this->registerJs($updateJs);

 ?>