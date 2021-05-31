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

 <audio id="myAudio" controls style="display: none">
     <source src="" id="myAu" type="audio/mpeg">
     您的浏览器不支持 audio 元素。
 </audio>

 <button id="kaiqi" onclick="f()">开启</button>
<script>
</script>
 <?php

 $updateJs = <<<JS
   $.get('http://hospital.child.wedoctors.com.cn/appoint-calling/ttl?text=是点击老师点击',function (e) {
       $('#myAu').attr('src',e.src);
       vMP3 = document.getElementById("myAudio");
        vMP3.play();
    })
JS;
 $this->registerJs($updateJs);

 ?>