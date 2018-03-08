<<!--div class="form-group">
    <label class="control-label col-md-3">选择研讨班账号<span class="required"> * </span></label>
    <div class="col-md-4">
        <select class="form-control" name="subid" id="subid">
            <option value="">请选择</option>
            <?php /*foreach ($select as $k => $v) { */ ?>
                <option value="<? /*= $k */ ?>" <?php /*if ($selected == $k) { */ ?>selected="selected"<?php /*} */ ?>><? /*= $v */ ?></option>
            <?php /*} */ ?>
        </select>
    </div>
</div>-->

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-comments"></i>Nestable List 1
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                    <a href="#portlet-config" data-toggle="modal" class="config">
                    </a>
                    <a href="javascript:;" class="reload">
                    </a>
                    <a href="javascript:;" class="remove">
                    </a>
                </div>
            </div>
            <div class="portlet-body ">
                <div class="dd" id="nestable_list_1">
                    <ol class="dd-list">
                        <li class="dd-item" data-id="1">
                            <div class="dd-handle">
                                Item 1
                            </div>
                        </li>
                        <li class="dd-item" data-id="2">
                            <div class="dd-handle">
                                Item 2
                            </div>
                            <ol class="dd-list">
                                <li class="dd-item" data-id="3">
                                    <div class="dd-handle">
                                        Item 3
                                    </div>
                                </li>
                                <li class="dd-item" data-id="4">
                                    <div class="dd-handle">
                                        Item 4
                                    </div>
                                </li>
                                <li class="dd-item" data-id="5">
                                    <div class="dd-handle">
                                        Item 5
                                    </div>
                                    <ol class="dd-list">
                                        <li class="dd-item" data-id="6">
                                            <div class="dd-handle">
                                                Item 6
                                            </div>
                                        </li>
                                        <li class="dd-item" data-id="7">
                                            <div class="dd-handle">
                                                Item 7
                                            </div>
                                        </li>
                                        <li class="dd-item" data-id="8">
                                            <div class="dd-handle">
                                                Item 8
                                            </div>
                                        </li>
                                    </ol>
                                </li>
                                <li class="dd-item" data-id="9">
                                    <div class="dd-handle">
                                        Item 9
                                    </div>
                                </li>
                                <li class="dd-item" data-id="10">
                                    <div class="dd-handle">
                                        Item 10
                                    </div>
                                </li>
                            </ol>
                        </li>
                        <li class="dd-item" data-id="11">
                            <div class="dd-handle">
                                Item 11
                            </div>
                        </li>
                        <li class="dd-item" data-id="12">
                            <div class="dd-handle">
                                Item 12
                            </div>
                        </li>
                    </ol>


                    <ol>
                        <li class="dd-item" data-id="11">
                            <div class="dd-handle">
                                Item 11
                            </div>
                        </li>
                        <li class="dd-item" data-id="12">
                            <div class="dd-handle">
                                Item 12
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$this->registerJs("
  jQuery(document).ready(function() {
    UINestable.init();
});
", \yii\web\View::POS_READY);
?>