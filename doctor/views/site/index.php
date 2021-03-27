<?php

/* @var $this yii\web\View */

$this->title = '首页';
hospital\assets\IndexAsset::register($this);

?>
<style>
    .info-box-content .title {
        font-size: 16px;
        line-height: 24px;
    }

    .info-box-content .item {
        line-height: 24px;
    }
</style>
<div class="col-xs-12">

    <div class="row">

    </div>
    <div class="row">

    </div>
    <script>
        var line_data =<?=json_encode($line_data)?>;


    </script>
</div>