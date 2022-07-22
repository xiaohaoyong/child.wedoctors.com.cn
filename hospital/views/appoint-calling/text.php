<br>
<br><br><br><br><br><br>
<common-audio ref="audioTip"></common-audio>
<button onclick="inputForm()">点击</button>
<script>
    const synth = window.speechSynthesis;
    inputForm();

    function inputForm (event) {
        alert('测试');
        const utterThis = new SpeechSynthesisUtterance('张三您好');
        synth.speak(utterThis);
    }
</script>