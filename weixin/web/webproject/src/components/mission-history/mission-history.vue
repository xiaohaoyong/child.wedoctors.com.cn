<template>
  <transition name="slide-left">

    <scroller class="mission_history" lock-x height="100vh" @on-scroll-bottom="onScrollBottom" ref="scrollerBottom" :scroll-bottom-offst="200">
      <!--<load-more v-if="!missionData.length" :show-loading="false" tip="没有宣教记录" background-color="#f8f8f8"></load-more>-->
      <div class="wrapper">
        <my-title>宣教记录</my-title>
        <!--<p v-for="i in missionData">placeholder {{i}}</p>-->
        <timeline>
          <timeline-item  v-for="(item,$index) in missionData" :key="$index">
            <div class="timeline_content">
              <router-link :to="`/article/${item.id}`" tag="div">
                <h4>{{item.createdata}}</h4>
                <p>{{item.title}}</p>
              </router-link>
            </div>
          </timeline-item>
          <!--<timeline-item>-->
            <!--<div class="timeline_content">-->
              <!--<h4>2017/09/15</h4>-->
              <!--<p>新生儿家庭护理健康教育2</p>-->
            <!--</div>-->
          <!--</timeline-item>-->
          <load-more tip="正在加载" :class="{'hide_icon': loadstatus}"></load-more>
          <load-more :class="{'hide_icon': !loadstatus}" :show-loading="false" tip="我也是有底线的" background-color="#f8f8f8"></load-more>
        </timeline>
      </div>
    </scroller>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import { Cell, Timeline, TimelineItem, XButton, Group,Scroller,LoadMore  } from 'vux'
  import {httpArticlelistuser} from 'my_api/missions';
  const ERR_OK = 200;

    export default {
        data() {
            return {
              page: 1,
              onFetching: false,//正在加载状态
              loadstatus: true,
              missionData: []
            }
        },
        created() {
          this.fetch(this.page);
        },
        methods: {
          fetch(nPage) {
            let self = this;
            self.loadstatus = false;//加载的提示
            self.onFetching = true;//控制可以按需加载的开关
            httpArticlelistuser(self.$route.query.id,nPage).then((response) => {
              if (response.code == ERR_OK) {
                let lists = response.data.list;
                if (lists) {
//                if (self.page<3){//暂时设置如果是第三页就无数据
//                  console.log(self.missionData.concat(lists));
//                  self.missionData = self.missionData.concat(lists)
                  self.missionData = [...self.missionData, ...lists];
                  self.page++;
                  self.$nextTick(() => {
                    self.$refs.scrollerBottom.reset()
                  })
                  self.onFetching = false;
                }
              }
              self.loadstatus = true;
            });
            /*self.$axios.get('/parent/article-user',{params: {//基本信息
              "id": self.$route.query.id,
              "page": nPage
            }}).then(function (response) {
              let data = response.data;
              if (data.code == ERR_OK) {
                let lists = data.data.list;
                if (lists) {
//                if (self.page<3){//暂时设置如果是第三页就无数据
//                  console.log(self.missionData.concat(lists));
//                  self.missionData = self.missionData.concat(lists)
                  self.missionData = [...self.missionData, ...lists];
                  self.page++;
                  self.$nextTick(() => {
                    self.$refs.scrollerBottom.reset()
                  })
                  self.onFetching = false;
                }
              }
              self.loadstatus = true;
            }).catch(function (error) {
              console.warn(error);
              self.$vux.toast.text('请刷新重试');
            });*/
          },
          onScrollBottom () {
            if (!this.missionData.length) return;//如果首次加载就没有数据就不需要再做按需加载的处理
            if (this.onFetching) {
//              this.loadstatus = false
              // do nothing
            } else {
              this.fetch(this.page)
            }
          }
        },
        components: {
          myTitle,Cell,Group,Timeline,
          TimelineItem,
          XButton,Scroller,LoadMore
        }
    }
</script>

<style lang="scss" rel="stylesheet/scss" >
  .mission_history{
    .hide_icon{
      visibility: hidden;
    }
    margin-top: unit(32rem);
    background-color: #f8f8f8;
    .vux-timeline .vux-timeline-item .vux-timeline-item-content .timeline_content{//强制修改时间轴的样式
      background-color: #fff !important;
      &:before{
        border-color: #ffffff #ffffff #f8f8f8 #f8f8f8 !important;
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
</style>
