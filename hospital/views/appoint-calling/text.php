<button onclick="actionList('请张三到第三诊室接种')">点击</button>
<script>
    function actionList(text){
        alert(text);
        let msg = new SpeechSynthesisUtterance(text);
        console.log(msg)
        //msg.rate = 4 播放语速
        //msg.pitch = 10 音调高低
        //msg.text = "播放文本"
        //msg.volume = 0.5 播放音量
        speechSynthesis.speak(msg);
    }
</script>