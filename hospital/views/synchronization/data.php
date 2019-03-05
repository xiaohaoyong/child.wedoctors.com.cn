<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/1/25
 * Time: 下午3:06
 */
$this->title = "上传、同步数据";
?>
<div class="child-info-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">上传文件（.xlsx)</h3>
                            </div>
                            <div class="box-body box-profile">

                                <?= \common\assets\FileInput::widget(
                                    [
                                        'name' => 'file',
                                        'options' => ['method' => 'post'],
                                        'pluginOptions' => [
                                            'dropZoneTitle' => '拖拽到这来上传',
                                            // 是否展示预览图
                                            'initialPreviewAsData' => false,
                                            // 异步上传的接口地址设置
                                            'uploadUrl' => 'http://wedoctorschild.oss-cn-beijing.aliyuncs.com',
                                            'enctype' => 'multipart/form-data',
                                            // 异步上传需要携带的其他参数，比如商品id等
                                            'uploadExtraData' => [
                                                'OSSAccessKeyId' => $row['accesskeyid'],
                                                'policy' => $row['policy'],
                                                'Signature' => $row['signature'],
                                                'key' => $row['key'],
                                                'callback' => $row['callback'],
                                                'success_action_redirect' => $row['success_action_redirect'],
                                                'success_action_status' => $row['success_action_status'],
                                            ],
                                            'uploadAsync' => true,
                                            // 最少上传的文件个数限制
                                            'minFileCount' => 1,
                                            // 最多上传的文件个数限制
                                            'maxFileCount' => 1,
                                            // 是否显示移除按钮，指input上面的移除按钮，非具体图片上的移除按钮
                                            'showRemove' => true,
                                            // 是否显示上传按钮，指input上面的上传按钮，非具体图片上的上传按钮
                                            'showUpload' => true,
                                            //是否显示[选择]按钮,指input上面的[选择]按钮,非具体图片上的上传按钮
                                            'showBrowse' => true,
                                            // 展示图片区域是否可点击选择多文件
                                            'browseOnZoneClick' => false,
                                        ],
                                        // 一些事件行为
                                        'pluginEvents' => [
                                            // 上传成功后的回调方法，需要的可查看data后再做具体操作，一般不需要设置
                                            "fileuploaded" => "function (event, data, id, index) {
            console.log(data);
        }",
                                        ],
                                    ]
                                ) ?>

                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->

                        <!-- About Me Box -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">注意：</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <p class="text-muted">1，目前支持.xlsx文件上传</p>
                                <hr>
                                <p class="text-muted">2，上传文件后会根据字段自动判断妇幼/体检数据</p>
                                <hr>
                                <p class="text-muted">3，文件上传成功后，后端会自动进行数据导入，请稍后查看导入状态</p>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#activity" data-toggle="tab">妇幼</a></li>
                                <li><a href="#timeline" data-toggle="tab">体检</a></li>
                                <li><a href="#settings" data-toggle="tab">孕期</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <table id="example2" class="table table-bordered table-hover"
                                           style="font-size: 12px;">
                                        <thead>
                                        <tr>
                                            <th>数据上传日期</th>
                                            <th>内容条数</th>
                                            <th>新增数据条数</th>
                                            <th>数据更新状态</th>
                                            <th>更新状态</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>小红门家庭医生团队</td>
                                            <td>2596</td>
                                            <td>2</td>
                                            <td>812</td>
                                            <td>0</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="timeline">
                                    <table id="example2" class="table table-bordered table-hover"
                                           style="font-size: 12px;">
                                        <thead>
                                        <tr>
                                            <th>数据上传日期</th>
                                            <th>内容条数</th>
                                            <th>新增数据条数</th>
                                            <th>数据更新状态</th>
                                            <th>更新状态</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>小红门家庭医生团队</td>
                                            <td>2596</td>
                                            <td>2</td>
                                            <td>812</td>
                                            <td>0</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="settings">
                                    <table id="example2" class="table table-bordered table-hover"
                                           style="font-size: 12px;">
                                        <thead>
                                        <tr>
                                            <th>数据上传日期</th>
                                            <th>内容条数</th>
                                            <th>新增数据条数</th>
                                            <th>数据更新状态</th>
                                            <th>更新状态</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>小红门家庭医生团队</td>
                                            <td>2596</td>
                                            <td>2</td>
                                            <td>812</td>
                                            <td>0</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>