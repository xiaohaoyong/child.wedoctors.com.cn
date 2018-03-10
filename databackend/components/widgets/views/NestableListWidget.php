
<div class="row">
    <div class="col-md-12">
        <h3>Serialised Output (per list)</h3>
        <textarea id="nestable_list_1_output" class="form-control col-md-12 margin-bottom-10"></textarea>
        <textarea id="nestable_list_2_output" class="form-control col-md-12 margin-bottom-10"></textarea>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-comments"></i></div>
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