<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/4/30
 * Time: 上午11:52
 */
\frontend\assets\ScrollAsset::register($this);
$this->title="关于我们-儿宝宝";
?>
    <div class="banner"
         style=" height:371px;background: url('/img/about_banner_img.png') no-repeat;background-position:right 85%;background-color: #C42A32; padding-bottom: 31px;">
        <div class="main">
            <div class="text" style="margin-left: 142px;margin-top: 156px;">
                <div class="title" style="font-size:58px;font-weight:400;color:rgba(255,255,255,1);line-height:44px;">
                    关于我们
                </div>
                <div class="info" style="font-size:29px;font-weight:400;color:rgba(255,255,255,1);line-height:44px;">
                    ABOUT
                    US
                </div>
            </div>
            <div class="img" style="padding-top: 70px;cursor:pointer;"  data-toggle='modal' data-target='#create-modal'>
                <div><img src="/img/about_banner_modal.png" width="52" height="48"></div>
                <div class="txt">预约上门演示DEMO</div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="bottom" style="padding: 0;margin-bottom: 20px;">
            <div class="navigation">
                <div class="title">
                    <div class="text">热点</div>
                    <div class="en">HOT SPOT</div>
                </div>
            </div>
        </div>
        <div class="hotlist">
            <div class="item">
                <div class="datetime">
                    <div class="year">2017</div>
                    <div class="date">12 . 05</div>
                </div>
                <div class="title">儿童中医药健康管理西城启动大会</div>
                <div class="info">
                    于12月5日在西城区社管中心会议室召开，参会主要有国家中医管理局领导，北京中医管理局领导，西城区社管中心领导、顾问专家，各社区卫生服务中心领导，北京市西城区预防医学会领导及项目组成员。项目组成员刘庆介绍项目具体情况以及汇报项目实施情况。
                </div>
            </div>
            <div class="item">
                <div class="datetime">
                    <div class="year">2018</div>
                    <div class="date">10 . 10</div>
                </div>
                <div class="title">儿童中医药健康管理海淀启动大会</div>
                <div class="info">
                    于10月10日在双榆树会议中心召开，参会主要有北京中医管理局领导，西城区社管中心领导、顾问专家，各社区卫生服务中心领导，项目组成员。
                </div>
            </div>
            <div class="item">
                <div class="datetime">
                    <div class="year">2018</div>
                    <div class="date">10 . 24</div>
                </div>
                <div class="title">儿童中医药健康管理昌平启动大会</div>
                <div class="info">
                    于10月24日在金隅凤山会议中心召开，参会主要有昌平区卫生计生委中医管理科，社管中心相关工作主管领导，妇幼保健院相关工作负责人，各社区卫生服务中心主管主任和相关工作负责人。
                </div>
            </div>
        </div>
        <div class="bottom" style="padding: 0; margin-top: 56px; margin-bottom: 20px;">
            <div class="navigation">
                <div class="title">
                    <div class="text">记事</div>
                    <div class="en">INFO</div>
                </div>
                <div class="button">
                    <a href="javascript:;" class="leftbutton leftbutton1" title="上一个"></a>
                    <a href="javascript:;" class="rightbutton rightbutton1" title="下一个"></a>
                </div>
            </div>
        </div>
        <div class="scroll-box">
            <div class="scroll-wrap js-picBox">
                <div class="scroll-list scroll-list1">
                    <ul class="scroll-item" style="padding-inline-start: 0;">
                        <li>
                            <img src="/img/about_content_info5.jpg" alt=""/>
                            <div class="clearfix printTxt">
                                <h3>西城区工作通报会</h3>
                                <p></p>
                            </div>
                        </li>
                        <li>
                            <img src="/img/about_content_info4.jpg" alt=""/>
                            <div class="clearfix printTxt">
                                <h3>回龙观社区培训现场</h3>
                                <p></p>
                            </div>
                        </li>

                        <li>
                            <img src="/img/about_content_info1.jpg" alt=""/>
                            <div class="clearfix printTxt">
                                <h3>北七家用户签约现场</h3>
                                <p></p>
                            </div>
                        </li>
                        <li>
                            <img src="/img/about_content_info6.jpg" alt=""/>
                            <div class="clearfix printTxt">
                                <h3>展览路现场扫码</h3>
                                <p></p>
                            </div>
                        </li>
                        <li>
                            <img src="/img/about_content_info2.jpg" alt=""/>
                            <div class="clearfix printTxt">
                                <h3>东小口培训现场</h3>
                                <p></p>
                            </div>
                        </li>
                        <li>
                            <img src="/img/about_content_info3.jpg" alt=""/>
                            <div class="clearfix printTxt">
                                <h3>海淀区启动大会</h3>
                                <p></p>
                            </div>
                        </li>


                    </ul>
                </div>
            </div>

        </div>
        <div class="map">
            <img src="/img/about_content_map.png" width="1110">
        </div>
    </div>

<?php
$updateJs = <<<JS
    
$(function(){
    if($('.scroll-list1').length){
        $('.scroll-list1').loopScroll({
            prevButton: '.leftbutton1',
            nextButton: '.rightbutton1',
            auto: true
        });
        $('.js-picBox li').hover(function() {
            $(this).find('.printTxt').animate({'top':'294px','bottom':'0'}, 1000)
        }, function() {
            $(this).find('.printTxt').animate({'top':'415px','bottom':'0'}, 1000)
        });
    }
});
JS;
$this->registerJs($updateJs);
?>