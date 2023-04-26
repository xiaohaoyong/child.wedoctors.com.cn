<?php
$this->title = "批量迁入迁出";  
?>
<div class="child-info-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                
                <div class="box box-primary">

                    
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <?= \common\assets\FileInput::widget(
                                    [
                                        'name' => 'file',
                                        'options' => ['method' => 'post'],
                                        'pluginOptions' => [
                                            'dropZoneTitle' => '上传社区迁出用户数据(excel)',
                                            // 是否展示预览图
                                            'initialPreviewAsData' => false,
                                            'allowedFileExtensions' => ['xlsx'],

                                            'maxFileSize' => 1000*6,
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
                                        "filepreajax" => "function (event, data, id, index,obj) {
                                        console.log(event);
                                        console.log(data);
                                        console.log(id);
                                        console.log(index);
                                        console.log(obj);
                                            var tmp = Date.parse( new Date() ).toString();
                                            tmp = tmp.substr(0,10);
                                            index.set('key',index.get('key')+'/'+tmp);
                                            }",
                                        ],
                                    ]
                                ) ?>
                        </div>
                        
                        <div class="box-body box-profile">
                            
                        <?= \common\assets\FileInput::widget(
                                    [
                                        'name' => 'file',
                                        'options' => ['method' => 'post'],
                                        'pluginOptions' => [
                                            'dropZoneTitle' => '上传社区迁入用户数据(excel)',
                                            // 是否展示预览图
                                            'initialPreviewAsData' => false,
                                            'allowedFileExtensions' => ['xlsx'],

                                            'maxFileSize' => 1000*6,
                                            // 异步上传的接口地址设置
                                            'uploadUrl' => 'http://wedoctorschild.oss-cn-beijing.aliyuncs.com',
                                            'enctype' => 'multipart/form-data',
                                            // 异步上传需要携带的其他参数，比如商品id等

                                            'uploadExtraData' => [
                                                'OSSAccessKeyId' => $row2['accesskeyid'],
                                                'policy' => $row2['policy'],
                                                'Signature' => $row2['signature'],
                                                'key' => $row2['key'],
                                                'callback' => $row2['callback'],
                                                'success_action_redirect' => $row2['success_action_redirect'],
                                                'success_action_status' => $row2['success_action_status'],
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
                                        "filepreajax" => "function (event, data, id, index,obj) {
                                        console.log(event);
                                        console.log(data);
                                        console.log(id);
                                        console.log(index);
                                        console.log(obj);
                                            var tmp = Date.parse( new Date() ).toString();
                                            tmp = tmp.substr(0,10);
                                            index.set('key',index.get('key')+'/'+tmp);
                                            }",
                                        ],
                                    ]
                                ) ?>    
                            
                        </div>
                        
                        <div class="box-body box-profile">
                            <a href="/baby.xlsx" target="_blank">宝宝表格模板下载</a>
                        </div>
                        <div class="box-body box-profile">
                            <a href="/mama.xlsx" target="_blank">孕妈表格模板下载</a>
                        </div>
                        
                        
                        
                    </div>
                    
                    
                    
                </div>
                
                
                
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">注意：</h3>
                    </div>
                    
                    <div class="box-body">
                        <p class="text-muted">1，目前支持.xlsx文件上传,最大支持5M文件</p>
                        <hr>
                        <p class="text-muted">2，上传文件后会根据字段自动判断妇幼/体检数据</p>
                        <hr>
                        <p class="text-muted">3，文件上传成功后，后端会自动进行数据导入，请稍后查看导入状态</p>
                    </div>
                    
                </div>
                
                <div class="box box-primary">
                    <div class="box-header with-border">
                         <h3 class="box-title">上传记录：</h3>
                    </div>
                    <div class="box-body">
                    <div id="w1" class="col-sm-12 table text-nowrap">
                   <table class="table table-striped table-bordered">
                   <thead>
                   <tr>
                   <td>ID</td>
                   <td>上传时间</td>
                   <td>上传用户类型</td>
                   <td>迁入/迁出</td>
                   <td>上传总条数</td>
                   <td>成功条数</td>
                   <td>失败条数</td>
                   <td>状态</td>
                   <td>MSG</td>
                   </tr>
                   </thead>
                   
                   <tbody>
                   <?php
				   if( empty($lists) )
				   { ?>
                   <tr><td colspan="9"><div class="empty">没有找到数据。</div></td></tr>
					<?php
                   }
				   else
				   {
					   foreach( $lists as $k => $v )
					   {
				   ?>
                   <tr>
                   <td><?php echo $v['id']; ?></td>
                   <td><?php echo date('Y-m-d H:i:s',$v['add_time']); ?></td>
                   <td><?php echo $v['file_type_num']?($v['file_type_num']==2?'宝宝':'孕妈'):'未分析'; ?></td>
                   <td><?php echo $v['type_num']?'迁入':'迁出'; ?></td>
                   <td><?php echo $v['sum_num']; ?></td>
                   <td><?php echo $v['s_num']; ?></td>
                   <td><?php echo $v['f_num']; ?></td>
                   <td><?php echo $v['lock_num']?($v['lock_num']==1?'执行中':'执行完毕'):'未执行'; ?></td>
                   <td><?php echo $v['msg_str']; ?></td>
                   </tr>
                   <?php
					   }
				   } ?>
                   </tbody>
                   </table>
                    </div> 
                    </div>
                </div>
                
                
            </div>
            
        </div>
    </div>
</div>
