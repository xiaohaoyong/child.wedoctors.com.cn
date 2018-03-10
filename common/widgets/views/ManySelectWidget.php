<div class="form-group">
    <div class="col-md-4">
                                             <span class="control-group" id="cat_input">
											<select id="select2_sample2" name='cati' class="form-control select2"
                                                    size=<?= $size ?> multiple>
                                                <?php
                                                foreach ($select as $kk => $vv) { ?>
                                                    <option <?= is_array($selected) ? (in_array($kk, $selected) ? "selected='selected'" : '') : '' ?>
                                                        value="<?= $kk ?>"> <?= $vv ?></option>
                                                <?php } ?>
                                            </select>
                                            </span>
        <input type="hidden" name="catid" id='cat' value="<?= implode(',', $select) ?>">
    </div>
</div>
<?php
$this->registerJs("
    jQuery(document).ready(function () {
        Layout.init(); 
         ComponentsDropdowns.init();
    });
        $('#select2_sample2').click(function(){
        var size=$(this).attr('size')
        var tag=$(this).val();
        $('#cat').val(tag);
        if(tag.length>size){
            $('.select2-choices li:eq(2)').children(1).trigger('click');
        }
    });
", \yii\web\View::POS_END);
?>