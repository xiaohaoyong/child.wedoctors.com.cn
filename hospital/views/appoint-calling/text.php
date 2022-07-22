<br>
<br><br><br><br><br><br>
<common-audio ref="audioTip"></common-audio>
<button onclick="inputForm()">点击</button>
<script>
    const synth = window.speechSynthesis;


    function inputForm (event) {
        const utterThis = new SpeechSynthesisUtterance('张三您好');
        synth.speak(utterThis);
    }
</script>