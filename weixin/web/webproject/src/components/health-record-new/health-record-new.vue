<template>
  <transition name="slide-left">
    <section class="health_record">
      <scroller lock-x height="100vh" @on-scroll-bottom="onScrollBottom" ref="scrollerBottom" :scroll-bottom-offst="100">
        <div class="scroll_box">
          <div class="info">
            <cell is-link :link="{path:'/infos-edit',query: {'id': Child.id,'type': 'parent'}}" >
              <img slot="icon" src="./avatar.png">
              <div slot="title">
                <span class="name">{{Child.name}}<i>{{Child.age}}</i></span>
                <span class="tel">{{Child.phone}}</span>
              </div>
            </cell>
          </div>

          <div class="health_history">
            <cell title="健康记录" class="title"></cell>

            <div v-if="recordCardList">
              <timeline>
                <timeline-item v-if="recordCardList[0].next">
                  <cell>
                    <div class="tip" slot="child">
                      <h3>下一次体检时间：{{recordCardList[0].next}}</h3>
                    </div>
                  </cell>
                </timeline-item>
                <timeline-item v-for="(item,$index) in recordCardList" :key="$index">
                  <cell>
                  <!-- <cell :link="`/record-data/${item.id}`"> -->
                    <div class="record_card" slot="child">
                      <!-- <div class="arrow">
                        <x-icon type="ios-arrow-forward" size="30"></x-icon>
                      </div> -->
                      <dl class="card">
                        <dt v-if="item.title || item.date">
                          <h4>{{item.title}}</h4>
                          <time>{{item.date}}</time>
                        </dt>
                        <dd>
                          <div>体重：{{item.tizhong}}kg</div>
                          <div>身长：{{item.shenchang}}cm</div>
                        </dd>
                        <dd>
                          <div>头围：{{item.touwei}}cm</div>
                          <div>BMI值：{{item.bmi}}</div>
                        </dd>
                        <dd>
                          <div>肥胖评价：{{item.feipang}}</div>
                          <div>发育评估：{{item.fayu}}</div>
                        </dd>
                        <dd>
                          <div>营养不良评价：{{item.yingyang || "暂无评价"}}</div>
                        </dd>
                      </dl>
                    </div>
                  </cell>
                </timeline-item>
              </timeline>
              <load-more :class="{'hide_icon': !loadstatus}" :show-loading="false" tip="暂无更多体检记录 " background-color="#fff"></load-more>
              <load-more tip="正在加载" :class="{'hide_icon': loadstatus}"></load-more>
            </div>

            <my-empty h="50vh" class="empty_box" v-else>
              <img :src="PicEmpty" slot="icon" class="icon">
              <p slot="text" style="color: #f3564c;" class="text">暂无更多体检记录</p>
            </my-empty>

          </div>

        </div>
      </scroller>


      <!--<aside class="tip_wrapper" v-show="show_tip">
          <div class="content">
            <h4>生长发育曲线说明<a href="javascript:;" class="close" @click="show_tip = false"></a></h4>
            <p>最下面的一条曲线为3%，意思是将有3%的婴幼儿低于这一水平，可能存在生长发育迟缓；最上面的一条曲线为97%，意思是将有3%的婴幼儿高于这一水平，可能存在生长过速。这两种情况都应该引起关注。中间的一条曲线为50%，代表平均值。</p>
            <p>我们经常谈及的正常值，应该是3%～97%涉及的范围。</p>
          </div>
      </aside>-->

      <my-tipbox v-show="show_tip">
        <div class="content" slot="child" v-show="show_tip">
          <h4>生长发育曲线说明<a href="javascript:;" class="close" @click="show_tip = false"></a></h4>
          <p>最下面的一条曲线为3%，意思是将有3%的婴幼儿低于这一水平，可能存在生长发育迟缓；最上面的一条曲线为97%，意思是将有3%的婴幼儿高于这一水平，可能存在生长过速。这两种情况都应该引起关注。中间的一条曲线为50%，代表平均值。</p>
          <p>我们经常谈及的正常值，应该是3%～97%涉及的范围。</p>
        </div>
      </my-tipbox>


      <!--<button class="fixed-btn" @click="add_new">添加宝宝记录</button>-->
    </section>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import myTipbox from '../../base/slide-tip/slide-tip.vue'
  import myEmpty from '@/base/empty-list/empty-list.vue'
  import { Cell, Timeline, TimelineItem, XButton, Group,Scroller,LoadMore } from 'vux'
  const ERR_OK = 200;
  import {formatTel,formatText,formatArticleTime} from  '../../common/js/vaildform'
  import {httpChildinfo,httpHealthrecord} from 'my_api/get';
  import {httpParentChildEx} from 'my_api/record';

  import PicEmpty from "@/common/img/empty-record.png"

  export default {
    name: 'record-parent',
    data() {
      return {
        PicEmpty,
        record: 111,
        show_tip: false,
        Child: {},
        showArticle: false,
        Article: [],
        Record: [],
        Record_page: 1,
        onFetching: false,//可以再次加载的开关
        loadstatus: true,//正在加载状态
//              missionData: 2
        recordCardList: null
      }
    },
    created() {
      let reg =  /[?&][^?&]+=[^?&]+/g; //匹配 ?或者&  +  非?或者&一个或多个 +  =  +  非?或者&一个或多个 + 全局
//          let arr = url.match(reg);
//          console.log(this.$route.query)
      this.fetch();
      // this.fetchRecord();
      this._fetchCardList(this.$route.query.id);//获取体检数据
    },
    methods: {
      fetch() {
        let self = this;
        httpChildinfo(self.$route.query.id).then((response) => {
          if (response.code == ERR_OK){

            self.Child = response.data.Child;

            if (response.data.Article.list.length){
              self.showArticle = true;
              self.Article = response.data.Article.list;
            }
            self.Article = response.data.Article.list;
//                self.Record = data.data.Record;
//                console.info(data.data);
          }else{
            self.$vux.toast.text('请求异常');
          }
        });
      },
      _fetchCardList(id) {
        httpParentChildEx(id).then((res) => {
          if (res.code == ERR_OK){
            console.log(res);
            this.recordCardList = res.data;
          }else {
            self.$vux.toast.text(res.msg);
          }
        }).catch((err) => {
          this.$vux.toast.text(res.msg);
        })
      },
      add_new() {
        this.$router.push({
          path: '/record-add',
          query: {
            id: this.Child.id
          }
        })
      },
      onScrollBottom () {
      }
    },
    filters: {
      formatPhone: formatTel,
      formatArticle: formatText,
      formatArticleTime: formatArticleTime
    },
    components: {
      myTitle,Cell,Group,Timeline,
      TimelineItem,
      XButton,Scroller,LoadMore,myTipbox,myEmpty
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss">
  .health_record{
    position: relative;
    height: 100vh;
    /*padding-bottom: unit(102rem);*/
    .scroll_box{
      /*padding-bottom:3.5rem;*/
      /*.hide_icon{*/
      /*visibility: hidden;*/
      /*}*/
    }
    .empty_box{
      margin-top: rem(10);
      background-color: #fff;
      .icon{
        width: rem(150);
      }
      .text{
        font-size: rem(28);
      }
    }
    .info{
      display: flex;
      background-color: #fff;
      .weui-cell__ft{
        &:after{
          content: " ";
          display: inline-block;
          height: 6px;
          width: 6px;
          border-width: 2px 2px 0 0;
          border-color: #c0c0c0;
          border-style: solid;
          -webkit-transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);
          transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);
          position: relative;
          top: -2px;
          position: absolute;
          top: 50%;
          margin-top: -4px;
          right: rem(30);
        }
      }
      .weui-cell{
        width: 100%;
        padding: rem(30) rem(20);
        img{
          width: unit(100rem);
          margin-right: unit(34rem);
        }
        .vux-label{
          span{
            display: block;
            padding: unit(12rem) 0;
            color: #1a1b1f;
            &.name{
              font-size: unit(32rem);
              i{
                padding-left: unit(22rem);
                font-style: normal;
                font-size: unit(26rem);
              }
            }
            &.tel{
              font-size: unit(24rem);
            }
          }
        }

      }
    }
    .mission_list{
      margin-top: unit(50rem);
      .weui-cells{ @extend %weui_no_border;}
      .weui-cell{
        padding-top: unit(30rem);
        padding-bottom: unit(30rem);
      }
      .vux-label{
        /*width: 90%;
		text-overflow: ellipsis;
		white-space: nowrap;
		overflow: hidden;*/
      }
      .vux-cell-bd{
        color: $color333;
      }
      .weui-cell__ft{
        color: $colorbbb;
      }
      .gray_font{
        .vux-cell-bd{
          color: $color999;
        }
        .weui-cell__ft{
          padding-right: 0;
          &:after{
            /*display: none;*/
          }
        }
      }
    }
    .health_history{
      margin-top: unit(10rem);
      background-color: #fff;
      .weui-cell__ft{
        display: none;
      }
      .vux-timeline-item-color{
        position: relative;
        background-color: #fff !important;
        border: unit(2rem) solid #f16a63 !important;
        box-shadow: 0px 0px 0px 2px #fff;
      }
      .vux-timeline-item-tail{
        width: unit(2rem);
        background-color: #f16a63 !important;
      }
      .vux-timeline-item-content{
        padding-bottom: 0;
      }
      .tip{
        line-height: rem(54);
        width: 100%;
        h3{
          margin-top: rem(-20);
          width: rem(464);
          height: rem(54);
          border: rem(1) dashed #ffb946;
          font-size: rem(24);
          background-color: #fff3e1;
          color: #d9964e;
          text-indent: 3em;
        }

      }
      .record_card{
        position: relative;
        width: 100%;
        margin-top: rem(-20);

        .arrow{
          position: absolute;
          right: rem(-32);
          top: 0;
          bottom: 0;
          margin: auto;
          width: rem(64);
          height: rem(64);
          background-color: rgba(#fda623,0.3);
          border-radius: 50%;
          @extend %centercontent;
          .vux-x-icon {
            width: rem(36);
            fill: #fff;
          }
        }
        .card{
          width: 100%;
          padding: rem(36) rem(34);
          border: 1px solid #e8e8e8;
          background-color: #ffffff;
          dt{
            margin-bottom: rem(20);
            display: flex;
            align-items: center;
            h4{
              font-size: rem(36);
            }
            time{
              text-align: right;
              flex: 1;
              color: #999999;
              font-size: rem(24);
            }
          }
          dd{
            display: flex;
            padding: rem(6) 0;
            font-size: rem(28);
            color: #666666;
            >div{
              line-height: rem(36);
              flex: 1;
            }
          }
        }
      }
      .title{
        padding-top: unit(30rem);
        padding-bottom: unit(30rem);
        .vux-label{
          color: #333333;
        }
        .vux-cell-bd{
          color: $color333;
        }
        &:after{
          width: 96%;
          right: 0;
          left: auto;
        }
      }
      //时间轴样式在app.设置全局
    }

    .tip_wrapper{
      .content{
        padding: rem(40) rem(30);
        width: 100%;
        background-color: #fff;
        transform: translate3d(0,0,0);
        transition:  0.3s;
        h4{
          position: relative;
          margin-bottom: rem(30);
          text-align: center;
          font-size: rem(36);
          font-weight: 500;
          .close{
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            margin: auto;
            width: rem(34);
            height: rem(34);
            background: url("./close.png") no-repeat center / contain;
          }
        }
        p{
          line-height: rem(50);
          text-indent: 1.5em;
          font-size: rem(32);
        }
      }
    }
  }
</style>
