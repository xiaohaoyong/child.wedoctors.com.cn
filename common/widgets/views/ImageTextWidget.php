<div class="tab-pane" id="tab_images">
    <div class="row">
        <div id="tab_images_uploader_filelist" class="col-md-6 col-sm-12">
        </div>
    </div>
    <table class="table table-bordered " id='new_imgage'>
        <thead>

        <tr role="row" class="heading">
            <th width="10%" class="text-center">
                图片
            </th>
            <th width="35%" class="text-center">
                图片描述
            </th>
            <th width="10%" class="text-center">
                图文序号
            </th>
        </tr>
        </thead>
        <tbody>
        <?php if ($model->id) { ?>
            <?php foreach ($imgtext as $k => $v) { ?>
                <tr>
                    <td class="text-center">
                        <div class="col-md-9">
                            <div class="<?= $v['image'] ? 'fileinput fileinput-exists' : 'fileinput fileinput-new' ?>"
                                 data-provides="fileinput" name="files">
                                <div class="fileinput-preview fileinput-exists thumbnail"
                                     id="<?= 'img_src' . ($k + 1) ?>" style="width:80px; height:60px;border:0">
                                    <?php if ($v['image']) {
                                        echo "<img src=" . $v['image'] . ">";
                                    } ?>
                                </div>
                                <div>
										<span class="badge badge-success default btn-file">
										<span class="fileinput-new">选择图片</span>
										<span class="fileinput-exists" id='clean'>
										换一张</span>
										<a href="#" id="clean<?= $k + 1 ?>" class="fileinput-exists"
                                           data-dismiss="fileinput"></a>
                                                <input type="file" name="<?= "img" . ($k + 1) ?>" value=''>
										</span>
                                </div>
                            </div>
                            <input type="hidden" name="imageid<?= $k + 1 ?>" value="<?= $v['id'] ?>">
                            <input type="hidden" name="img<?= $k + 1 ?>" id='img<?= $k + 1 ?>'
                                   value="<?= $v['image'] ?>">
                        </div>
                    </td>


                    <td>
                        <div class="form-group">
                            <div class="col-md-9">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <textarea name="description<?= $k+1?>" id="description<?= $k+1 ?>"
                                              class="form-control"
                                              style="width:120%;height:80px;"><?= $v['content'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="text-center">

                        <?php if (($k + 1) < 4) { ?>
                            <div class="form-group last">
                                <select class="bs-select form-control input-small btn" id='mySelect<?= $k + 1 ?>'
                                        name="mySelect<?= $k + 1 ?>" style='width:10px'>
                                    <option value=''>选择封面序号</option>
                                    <option <?= ($k + 1) == 1 ? 'selected="selected"' : '' ?> value="1">封面一</option>
                                    <option <?= ($k + 1) == 2 ? 'selected="selected"' : '' ?> value='2'>封面二</option>
                                    <option <?= ($k + 1) == 3 ? 'selected="selected"' : '' ?> value='3'>封面三</option>
                                </select>
                            </div>
                        <?php } elseif (($k + 1) > 3 && ($k + 1) < 6) { ?>
                            <a href="javascript:;" class="btn default btn-sm">
                                <span class="badge badge-danger"><?= $k + 1 ?></span>图文编辑</a>
                        <?php } elseif (($k + 1) > 5) { ?>
                            <a href="javascript:;" onclick="$(this).parent().parent().remove();"
                               class="btn default btn-sm">
                                <span class="badge badge-danger"><?= $k + 1 ?></span>移除图文编辑<i
                                    class="fa fa-times"></i></a>
                        <?php } ?>

                    </td>


                </tr>
            <?php } ?>

        <?php } else { ?>
            <?php
            foreach ([1, 2, 3, 4, 5] as $v) {
                ?>
                <tr>
                    <td class="text-center">
                        <div class="col-md-9">
                            <div class="fileinput fileinput-new" data-provides="fileinput">

                                <div class="fileinput-preview fileinput-exists thumbnail" id="img_src<?=$v?>"
                                     style="height:60px;border:0">
                                </div>
                                <div>
													<span class="badge badge-success default btn-file">
													<span class="fileinput-new">选择图片</span>
													<span class="fileinput-exists">
													换一张</span>
													<a href="#" id='clean<?=$v?>' class="fileinput-exists"
                                                       data-dismiss="fileinput"></a>
													<input type="file" name="img<?= $v ?>" value=''>
													</span>
                                </div>
                            </div>
                            <input type="text" name="files<?= $v ?>" readonly="true"
                                   style='border-left:0px;border-top:0px;border-right:0px;border-bottom:1px '
                                   id='files<?= $v ?>' value="">
                            <input type="hidden" name="img<?= $v ?>" id='img<?= $v ?>' value="">
                        </div>
                    </td>

                    <td>
                        <div class="form-group">
                            <div class="col-md-9">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <textarea name="description<?=$v?>" id="description<?=$v?>"
                                              class="form-control maxlength_prompt" maxlength=300
                                              style="width:120%;height:80px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <?php if ($v < 4) { ?>
                            <div class="form-group last" id='select1'>
                                <select class="bs-select form-control input-small btn" id='mySelect1' name='mySelect1'
                                        style='width:10px'>
                                    <option value=''>选择封面序号</option>
                                    <option selected="selected" value="1">封面一</option>
                                    <option value='2'>封面二</option>
                                    <option value='3'>封面三</option>
                                </select>
                            </div>
                        <?php } else { ?>
                            <a href="javascript:;" class="btn default btn-sm">
                                <span class="badge badge-danger"><?= $v ?></span>图文编辑</a>
                        <?php } ?>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>
    <span  style='color: red ' class='help-block'>*默认5个图文编辑为必填内容，图片为jpg格式，不大于1.5M，文本编辑至少输入20个字符，最多输入300个字符,一次最多上传50个图文*</span>
    <div style='text-align: center;'>
        <span class="btn purple " id='imgadd'>添加图文编辑</span>
    </div>
</div>
<script>
    var length = $("#new_imgage tr").length;
    for (var i = 1; i < length; i++) {
        var id = '#description' + i;
        $("body").on("change", id, function () {
//alert(id)
        });
    }
</script>
<script>
    $('#channels li a').click(function () {
        var channl1 = $(this).html();
        var id = $(this).attr('id');
        if (id != 1) {
            $('#addvalue').html(channl1);
            $('#channel').val(id);
        }
        $("#succ span").css('display', 'none')
        $("#pin").attr('class', 'form-group has-success')
    });
</script>
<script>
    $('#tags').click(function () {
        var tag = $('#s2id_tags ul li');
        lengs = tag.length;
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
</script>
<script>
    /* function getObjectURL(file) {
     var url = null;
     if (window.createObjectURL != undefined) {
     url = window.createObjectURL(file)
     }
     else if (window.URL != undefined) {
     url = window.URL.createObjectURL(file)
     }
     else if (window.webkitURL != undefined) {
     url = window.webkitURL.createObjectURL(file)
     }
     return url
     }; */
    $("body").on("change", "#new_imgage tr", function () {
        img = '#img' + ($(this).index($("input[name='file']").parent()[0]) + 1);
        clean = '#clean' + ($(this).index($("input[name='file']").parent()[0]) + 1);

        /*if (($(img).val().indexOf("jpg")<0 || $(img).val().indexOf("JPG")>0 || $(img).val().indexOf("")<0 ) && $(img).val()!='' ) {
         alert('请选择图片类型为jpg格式，点击确定图片自动清空');
         $(clean).trigger('click');
         }*/
        var fileInput = $(img)[0]
        byteSize = fileInput.files[0].size;
        filesize = Math.ceil(byteSize / 1024);
        if (filesize > 204800) {
            alert('请选择图片大小不超过1.5M,点击确定图片自动清空');
            $(clean).trigger('click');
        }


        /* var img = new Image();
         img.onload = function(){
         if(this.height>351){
         //alert("请所选图片高度不超过350px");
         //$(clean).trigger('click');
         }

         };
         if ($.browser.msie) {
         try {
         img.src = getObjectURL(fileInput.files[0]);
         }
         catch (e) {
         img.src = this.value;
         }
         }else{
         img.src = getObjectURL(fileInput.files[0]);
         } */
    })


</script>
<script>
    $('#mySelect1').change(function () {
        myselect1 = $(this).children('option:selected').val();
        myselect2 = $('#mySelect2').children('option:selected').val();
        myselect3 = $('#mySelect3').children('option:selected').val();
        $('#description1').attr('name', 'description' + myselect1);
        $('#img1').attr('name', 'img' + myselect1);
        $('#files1').attr('name', 'files' + myselect1);
        if ((myselect1 == myselect2 && myselect1 != 0) || (myselect1 == myselect3 && myselect1 != 0)) {
            alert('该封面已被选取，请重新选择');
            $(this).val("");
        }
    });
    $('#mySelect2').change(function () {
        myselect2 = $(this).children('option:selected').val();
        myselect1 = $('#mySelect1').children('option:selected').val();
        myselect3 = $('#mySelect3').children('option:selected').val();
        $('#description2').attr('name', 'description' + myselect2);
        $('#img2').attr('name', 'img' + myselect2);
        $('#files2').attr('name', 'files' + myselect2);
        if ((myselect2 == myselect1 && myselect2 != 0) || (myselect2 == myselect3 && myselect2 != 0)) {

            alert('该封面已被选取，请重新选择');
            $(this).val("");
        }
    });
    $('#mySelect3').change(function () {
        myselect3 = $(this).children('option:selected').val();
        myselect2 = $('#mySelect2').children('option:selected').val();
        myselect1 = $('#mySelect1').children('option:selected').val();
        $('#description3').attr('name', 'description' + myselect3);
        $('#img3').attr('name', 'img' + myselect3);
        $('#files3').attr('name', 'files' + myselect3);
        if ((myselect3 == myselect2 && myselect3 != 0) || (myselect3 == myselect1 && myselect3 != 0)) {
            alert('该封面已被选取，请重新选择');
            $(this).val("");
        }
    });
</script>


<script>
    <?php $this->beginBlock('js_end')?>
    $($("input[type='file']")).live('change', function () {
        var formData = new FormData();
        formData.append('file', $(this)[0].files[0]);
        var file = $(this);
        var val = file.attr('name');
        var error = $(this).attr('name');
        var errorid = error.replace('img', 'files');
        $('#' + errorid + '-error').html('');
        $('#'.error).html();
        $.ajax({
            url: "<?=\yii\helpers\Url::toRoute('article/upload_img', true)?>",
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: formData,
            processData: false,
            contentType: false
        }).done(function (res) {
            file.after(file.clone().val(''));
            file.remove();
            $('#' + val).attr('value', res);
        }).fail(function (res) {
        });
    });
    <?php $this->endBlock()?>
</script>

<?php $this->registerJs($this->blocks['js_end'], \yii\web\View::POS_END); ?>




<?php
$this->registerJs("
 $('#imgadd').click(function(){
        var length=$('#new_imgage tr').length;
        img='img'+length;
        clean='clean'+length;
        img_src='img_src'+length; 
        description='description'+length;
        errors='error'+length;
        filess='img'+length;
        tuerror='tuerror'+length;
       var html='<tr>\
<td class=\"text-center\">\
<div class=\"col-md-9\">\
<div class=\"fileinput fileinput-new\" data-provides=\"fileinput\">\
<div class=\"fileinput-preview fileinput-exists thumbnail\" id='+img_src+'  style=\"width:80px; height:60px;border:0\">\
</div>\
<div>\
<span class=\"badge badge-success default btn-file\">\																										<span class=\"fileinput-new\">选择图片</span>\
<span class=\"fileinput-exists\" id=\"clean\">\
换一张</span>\
<a href=\"#\" id='+clean+' class=\"fileinput-exists\" data-dismiss=\"fileinput\"></a>\
<input type=\"file\"    name='+img+' value=\"\">\
</span>\
</div>\
</div>\
<input type=\"hidden\" id='+img+' name='+filess+'  value=\"\">\
</div>\
</td>\
<td>\
<div class=\"form-group\">\
<div class=\"col-md-9\">\
<div class=\"input-icon right\">\
<i class=\"fa\"></i>\
<textarea name='+description+' id='+description+' class=\"form-control\" style=\"width:120%;height:80px;\" ></textarea>\
</div>\
</div>\
</div>\
</td>\
<td class=\"text-center\">\
<a href=\"javascript:;\" onclick=\"$(this).parent().parent().remove();\" class=\"btn default btn-sm\">\
<span class=\"badge badge-danger\">'+length+'</span>移除图文编辑<i class=\"fa fa-times\"></i></a>\
</td>\
</tr>\
'
	var table = $('#new_imgage');
	if(length<50){
		table.append(html);
	}else{
		alert('最多只能添加50个图文编辑');
	}
});
", \yii\web\View::POS_END);
?>

