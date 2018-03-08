<template>
  <transition name="slide-left">
  <section class="health_record_docter">
    <scroller lock-x height="100vh" @on-scroll-bottom="onScrollBottom" ref="scrollerBottom" :scroll-bottom-offst="100">
      <div class="scroll_box">
        <my-title>我的健康档案</my-title>
        <div class="info">
          <cell is-link :link="{path:'/infos-edit',query: {'id': Child.id,'type': 'docter'}}" value="详细信息">
            <img slot="icon" src="./avatar.png">
            <div slot="title">
              <span class="name">{{Child.name}}<i>{{Child.age}}</i></span>
              <span class="tel"><b class="icon"></b>{{Child.phone | formatPhone}}</span>
            </div>
          </cell>
        </div>
        <group class="mission_list" v-if="showArticle">
          <cell title="宣教记录" value="查看全部" :link="{path: '/mission-history',query: {'id':Child.id}}"></cell>
          <cell :link="`/article/${item.id}`" :key="item.id" :title="item.title | formatArticle" :value="item.createdata | formatArticleTime" class="gray_font" v-for="item in Article"></cell>
          <!--<cell title="新生儿家庭护理健康教育" value="09/13" class="gray_font"></cell>-->
        </group>

        <div class="health_history">
          <cell title="健康记录" class="title vux-1px-b"></cell>
          <timeline>
            <timeline-item v-for="item in Record" :key="item.id">
              <div class="timeline_content">
                <h4>{{item.createdata}} {{item.username}}记录</h4>
                <p>{{item.content}}</p>
              </div>
            </timeline-item>
          </timeline>
          <load-more :class="{'hide_icon': !loadstatus}" :show-loading="false" tip="我也是有底线的" background-color="#fff"></load-more>
          <load-more tip="正在加载" :class="{'hide_icon': loadstatus}"></load-more>
        </div>
      </div>
    </scroller>
    <button class="fixed-btn" @click="add_new">添加健康记录</button>
  </section>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import { Cell, Timeline, TimelineItem, XButton, Group,Scroller,LoadMore } from 'vux'
  import {httpDocterChildinfo} from 'my_api/get';
  import {formatTel,formatText,formatArticleTime} from  '../../common/js/vaildform'
const ERR_OK = 200;
  export default {
        data() {
            return {
              Child: [],
              showArticle: false,
              Article: [],
              Record: [],
              page: 1,
              onFetching: false,//可以再次加载的开关
              loadstatus: true,//正在加载状态
              missionData: 2
            }
        },
        created() {
          this.fetch();
          this.fetchHistory(this.page);
        },
        methods: {
          add_new() {
            this.$router.push({
              path: '/record-add',
              query: {
                id: this.Child.id
              }
            })
          },

          onScrollBottom () {
            if (this.onFetching) {
//              this.loadstatus = false
              // do nothing
            } else {
              this.fetchHistory(this.page)
            }
          },
          fetch() {
            let self = this;
            httpDocterChildinfo(self.$route.query.id).then((response) => {
              if (response.code == ERR_OK){

                self.Child = response.data.Child;
                if (response.data.Article.list.length){
                  self.showArticle = true
                  self.Article = response.data.Article.list;
                }
                self.Article = response.data.Article.list;
//                self.Record = data.data.Record;
//                console.info(data.data);
              }else{
                self.$vux.toast.text('请求异常');
              }
            });
            /*self.$axios.get('/parent/child-info',{params: {//基本信息
              id: self.$route.query.id
            }}).then(function (response) {
              let data = response.data;
              if (data.code == ERR_OK){

                self.Child = data.data.Child;

                if (data.data.Article.list.length){
                  self.showArticle = true
                  self.Article = data.data.Article.list;
                }
                self.Article = data.data.Article.list;
//                self.Record = data.data.Record;
//                console.info(data.data);
              }else{
                self.$vux.toast.text('请求异常');
              }
            }).catch(function (error) {
              self.$vux.toast.text('网络错误');
            });*/
          },
          fetchHistory(nPage) {
            let self = this;
            self.loadstatus = false;//加载的提示
            self.onFetching = true;//暂时可以打开再次渲染的

            self.$axios.get('/parent/health-record',{params: {//基本信息
              "id": self.$route.query.id,
              "page": nPage
            }}).then(function (response) {
              let data = response.data;
              if (data.code == ERR_OK){
//                  if (self.page < 3){//暂时设置如果是第三页就无数据
                if (data.data.list.length){
//                    console.log(data.data.list);
                  self.Record = [...self.Record,...data.data.list];
                  self.page++;
                  self.$nextTick(() => {
                    self.$refs.scrollerBottom.reset()
                  })
                  self.onFetching = false;
                }
                self.loadstatus = true;
              }
            }).catch(function (error) {
              console.warn(error);
              self.$vux.toast.text('请刷新重试');
            });
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
          XButton,Scroller,LoadMore
        }
    }
</script>

<style lang="scss" rel="stylesheet/scss">
.health_record_docter{
  position: relative;
  height: 100vh;
  /*padding-bottom: unit(102rem);*/
  .scroll_box{
    padding-bottom:3.5rem;
    .hide_icon{
      visibility: hidden;
    }
  }
  .info{
    display: flex;
    padding: unit(32rem);
    background-color: #fff;
    .weui-cell{
      width: 100%;
      img{
        width: unit(146rem);
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
            .icon{
              display: inline-block;
              margin-right: unit(14rem);
              vertical-align: middle;
              width: unit(32rem);
              height: unit(32rem);
              background: url("./icon-tel.png") no-repeat center / contain;
            }
          }
        }
      }
      .weui-cell__ft{
        font-size: unit(24rem);
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
          display: none;
        }
      }
    }
  }
  .health_history{
    margin-top: unit(32rem);
    background-color: #fff;
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
}
</style>
