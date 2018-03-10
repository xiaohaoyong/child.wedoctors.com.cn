<?php
$obj = explode('\\', get_class($model));
$att = strtolower($obj[3]) . '-' . $attribute;
?>
<input type="text" name='<?= $obj[3] . "[" . $attribute . "]" ?>'  id="tags" size=10 class="form-control select2_sample3" style=""
       value="<?= $tag ?>">


<script>
    <?php $this->beginBlock('js_end')?>
    $('#tags').click(function () {
        var tag = $('#s2id_tags ul li');
        lengs = tag.length;
        $('#tags-error').html('');
        if (lengs > 6) {
            alert('请输入的标签最多不超过5个,超过5个则自动清除该标签');
            tag.eq(tag.length - 2).remove();
        }
//tag.slice(5,tag.length).remove();
        leng = tag.eq(tag.length - 2).find('div').html().length;
        if (leng > 10) {
            alert('请输入的字符最多不超过10字符,超过10字符则自动清除该标签');
            tag.eq(tag.length - 2).remove();
        }
    });
    <?php $this->endBlock()?>
</script>
<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>