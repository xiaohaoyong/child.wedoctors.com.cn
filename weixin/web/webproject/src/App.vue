<template>
  <section id="app">
    <keep-alive>
      <router-view v-if="$route.meta.keepAlive" v-wechat-title="$route.meta.title"></router-view>
    </keep-alive>
    <router-view v-if="!$route.meta.keepAlive" v-wechat-title="$route.meta.title"></router-view>
    <!--<router-view ></router-view>-->
    <loading v-model="isLoading"></loading>
  </section>
</template>

<script type="text/ecmascript-6">
import { Loading } from 'vux'
import { mapState } from 'vuex'

export default {
  name: 'app',
  components: {
    Loading
  },
  computed: {
    ...mapState({
      isLoading: state => state.loading.isLoading
    })
  }
}
</script>
<style lang="less">
  @import '~vux/src/styles/1px.less';
</style>
<style lang="scss" rel="stylesheet/scss">
body{
  background-color: $colorbg;
  height: 100%;
  overflow-x: hidden;
}
  #app{
    .hide_icon{
      visibility: hidden;
    }

    .slide-left-enter-active {
      /*显示的开始加的状态  结束之后会被移除*/
      transition: transform 0.3s;
    }
    .slide-left-leave-active {
      /*隐藏的开始加的状态   结束之后会被移除*/
      transition: transform 0s;
    }
    .slide-left-enter, .slide-left-leave-active {
      /*过渡的状态*/
      transform: translateX(100%);
    }

    .slide-in-enter-active{
      transition: 0.5s;
    }
    .slide-in-leave-active{
      transition: 0s;
    }
    .slide-in-enter,.slide-in-leave-active{
      opacity: 0;
    }

    .slide-slidedown-enter-active{
      transition: 0.3s;
    }
    .slide-slidedown-leave-active{
      transition: 0.1s;
    }
    .slide-slidedown-enter,.slide-slidedown-leave-active{
      transform: translate3d(0,-10px,0);
      opacity: 0;
    }

    .vux-timeline{/*自定义时间轴的样式*/
      .vux-timeline-item-head{
        background-color: #85c8dd;
        border: unit(3rem) solid #fff;
      }
      .vux-timeline-item-head-first{
        border: unit(3rem) solid #fff;
        width: 10px;
        height: 10px;
        left: 1px;
        top: 4px;
        background-color: #85c8dd;
        border-radius: 99px;
      }
      .vux-timeline-item-checked{
        display: none;
      }
      .vux-timeline-item{
        &:last-child{
          .vux-timeline-item-tail{
            display: block !important;
          }
        }
        .vux-timeline-item-content{
          position: relative;
          padding-top: unit(16rem);
          .timeline_content{
            position: relative;
            margin-left: unit(35rem);
            padding: unit(29rem) unit(35rem) unit(32rem);
            background-color: #f8f8f8;
            h4{
              font-size: unit(24rem);
              color: $colorbbb;
            }
            p{
              line-height: 1.33333;
              padding-top: unit(17rem);
              font-size: unit(30rem);
              color: #555;
            }
            &:before{
              position: absolute;
              content: '';
              left: unit(-35rem);
              top: 0;
              width:0; height:0;
              border-width:unit(11rem) unit(20rem);
              border-style:solid;
              border-color:#f8f8f8 #f8f8f8 #ffffff #ffffff;
            }
          }
        }
      }
    }
  }
</style>
