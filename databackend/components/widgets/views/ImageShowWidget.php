<?php
$obj = explode('\\', get_class($model));
$att = $obj[3];
$id = strtolower($obj[3]) . '-' . $attribute;
?>
<table id='new_imgage'>
<tr id="Manyimg" >
    <?php if($model->id){
        foreach($images as $ks=>$vs){
    ?>
        <th>
            <div class="form-group last">
                <div class="col-md-1">
                    <div
                        class="<?= $vs['image']? 'fileinput fileinput-exists' : 'fileinput fileinput-new' ?>"
                        data-provides="fileinput">

                        <div class="fileinput-preview fileinput-exists thumbnail" style="width: 200px; height: 150px;border:0" id="<?='img_src'.($ks+1)?>">
                            <img src= '<?= $vs['image'] ?>'  style="width: 180px;height: 120px">
                        </div>
                    </div>
                    <input type="hidden"  name="<?="img[$vs[id]]"?>"  value="<?=$vs['image']?>">
                </div>
            </div>
        </th>
<?php } }?>
</tr>
    <tr id="tdimg"></tr>
</table>


<script>
    <?php $this->beginBlock('js_end')?>
    $($("input[type='file']")).live('change', function(){
        //获取图片的值
        var formData = new FormData();
        formData.append('file', $(this)[0].files[0]);
      var  file=$(this);
      var  val=file.attr('name');
        $.ajax({
            url:"<?=\yii\helpers\Url::toRoute('article/upload_img',true)?>",
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: formData,
            processData: false,
            contentType: false
        }).done(function(res) {
          //清空type=file中的值
          file.after(file.clone().val(''));
            file.remove();
            //改变隐藏框中图片的值
          $('#'+val).attr('value',res);

        }).fail(function(res) {

        });
    });
    $('#imgType').change(function(){
        var val=$(this).val();
        $('#tdimg').empty();
        var html = '';
        if(val==0){
            $('#Manyimg').empty();
        }
        if(val==1){
            html="<th ><div class='form-group last'><div class='col-md-1'><div class="+"<?=$model[$attribute]?"'fileinput fileinput-exists'":"'fileinput fileinput-new'"?>"+"data-provides='fileinput'> " +
                "<div class='fileinput-preview fileinput-exists thumbnail' id='img_src1'   style='width: 200px; height: 150px;border:0'> " +
                "<img src= "+"<?= $model[$attribute] ?>"+">" +
                "</div><div><span class='btn default btn-file'> " +
                "<span class='fileinput-new'>封面一</span>" +
                "<span class='fileinput-exists'>换一张</span> <input type='file' id="+"'<?=$id?>'"+"name='img1'></span>" +
                "<a href='#' class='btn red fileinput-exists' id='clean' data-dismiss='fileinput'>取消图片</a></div></div>" +
                "<input type='hidden' name='img1' id='img1'   value="+"<?= $model[$attribute] ?>"+"></div></div></th>";
                $('#Manyimg').html(html);
        }
        if(val==2){
            html="<th ><div class='form-group last'><div class='col-md-1'><div class="+"<?=$model[$attribute]?"'fileinput fileinput-exists'":"'fileinput fileinput-new'"?>"+"data-provides='fileinput'> " +
                "<div class='fileinput-preview fileinput-exists thumbnail' id='img_src1'   style='width: 200px; height: 150px;border:0'> " +
                "<img src= "+"<?= $model[$attribute] ?>"+">" +
                "</div><div><span class='btn default btn-file'> " +
                "<span class='fileinput-new'>封面一</span>" +
                "<span class='fileinput-exists'>换一张</span> <input type='file' name='img1'></span>" +
                "<a href='#' class='btn red fileinput-exists' id='clean' data-dismiss='fileinput'>取消图片</a></div></div>" +
                "<input type='hidden' name='img1' id='img1'  value="+"<?= $model[$attribute] ?>"+"></div></div></th>" +

                "<th ><div class='form-group last'><div class='col-md-1'><div class="+"<?=$model[$attribute]?"'fileinput fileinput-exists'":"'fileinput fileinput-new'"?>"+" data-provides='fileinput'> " +
                "<div class='fileinput-preview fileinput-exists thumbnail' id='img_src2'   style='width: 200px; height: 150px;border:0'> " +
                "<img src= "+"<?= $model[$attribute] ?>"+">" +
                "</div><div><span class='btn default btn-file'> " +
                "<span class='fileinput-new'>封面二</span>" +
                "<span class='fileinput-exists'>换一张</span> <input type='file' name='img2'></span>" +
                "<a href='#' class='btn red fileinput-exists' id='clean' data-dismiss='fileinput'>取消图片</a></div></div>" +
                "<input type='hidden' name='img2' id='img2'  value="+"<?= $model[$attribute] ?>"+"></div></div></th>" +

                "<th ><div class='form-group last'><div class='col-md-1'><div class="+"<?=$model[$attribute]?"'fileinput fileinput-exists'":"'fileinput fileinput-new'"?>"+" data-provides='fileinput'> " +
                "<div class='fileinput-preview fileinput-exists thumbnail' id='img_src3'   style='width: 200px; height: 150px;border:0'> " +
                "<img src= "+"<?= $model[$attribute] ?>"+">" +
                "</div><div><span class='btn default btn-file'> " +
                "<span class='fileinput-new'>封面三</span>" +
                "<span class='fileinput-exists'>换一张</span> <input type='file' name='img3'></span>" +
                "<a href='#' class='btn red fileinput-exists' id='clean' data-dismiss='fileinput'>取消图片</a></div></div>" +
                "<input type='hidden' name='img3' id='img3'  value="+"<?= $model[$attribute] ?>"+"></div></div></th>";
            $('#Manyimg').html(html);
        }
        if(val==3){
            var  content= $('#article-content').val();
            var imgReg = /<img.*?(?:>|\/>)/gi;
            var srcReg = /src=['"]?([^'"]*)['"]?/i;
            var arr = content.match(imgReg);
            if(arr!=null) {
                var td = '';
                for (var i = 0; i < arr.length; i++) {
                    var src = arr[i].match(srcReg);
                    var id = 'tu' + (i + 1);
                    html += "<th class='text-center'><div style='width: 100px' id=img_src"+(i+1)+"><img id=" + id + " src='" + src[1] + "'style='width:80px;height:60px'>" +
                        "</div></th>";
                    td += "<td class='text-center' valign='top'><input name='img[]'  type='checkbox' value=" + src[1] + " /></td>";
                }
                $('#Manyimg').html(html);
                $('#tdimg').html(td);
            }else{
                html="<td style='color:red' valign='bottom'>* 该文章中没有图片,将默认为无封面文章 *</td>";
                $('#Manyimg').html(html);
            }
        }
    });
    <?php $this->endBlock()?>
</script>

<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END);?>

