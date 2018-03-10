<template>
  <div class="parents_classroom">
    <my-title>家长课堂</my-title>
    <div>
      <tab :line-width=2 active-color='#f57961' v-model="index" :custom-bar-width="getBarWidth">
        <tab-item class="vux-center" :selected="demo2 === item" v-for="(item, index) in list2" @click="demo2 = item" :key="index">{{item}}</tab-item>
      </tab>
      <swiper v-model="index" height="" :show-dots="false">
        <swiper-item v-for="(item, index) in list2" :key="index">
          {{item}}
          <div class="tab-swiper vux-center">
            <ul class="articles_wrapper">
              <li class="vux-1px-b">
                <a href="javascript:;">
                  <div class="desc">
                    <h3>如果你无法简洁的表达你的
                      想法,那指说明你还不够了解
                      它   爱因斯坦
                    </h3>
                    <p>关于母乳喂养，刨宫妈妈要提问</p>
                  </div>
                  <img src="./article_img.png" class="pic">
                </a>
              </li>
              <li class="vux-1px-b"><a href="javascript:;">
                <div class="desc">
                  <h3>如果你无法简洁的表达你的
                    想法,那指说明你还不够了解
                    它   爱因斯坦
                  </h3>
                  <p>关于母乳喂养，刨宫妈妈要提问</p>
                </div>
                <img src="./article_img.png" class="pic">
              </a></li>
              <li class="vux-1px-b">{{item}} Container</li>
            </ul>
          </div>
        </swiper-item>
      </swiper>
    </div>
  </div>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import { Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem,Group,Cell } from 'vux'
  const list = () => ['推荐', '视频', '饮食', '运动', '育儿保健']

    export default {
        data() {
            return {
              list2: list(),
              demo2: '视频',
              index: 0,
              getBarWidth: function (index) {
                return  22 + 'px'
                return (index + 1) * 22 + 'px'
              }
            }
        },
        methods: {},
        components: {myTitle,Tab,
          TabItem,
          Sticky,
          Divider,
          XButton,
          Swiper,
          SwiperItem,Group,Cell}
    }
</script>

<style lang="scss" rel="stylesheet/scss">
.parents_classroom{
  .vux-tab{
    background-color: #f4f5f6;
  }
  .articles_wrapper{
    li{
      a{
        display: flex;
        padding: unit(40rem) unit(30rem);
        &:active{
          opacity: 0.7;
        }
      }
      .desc{
        display: flex;
        flex-flow: column;
        justify-content: space-around;
        flex: 1;
        >h3{
          color: #222;
          font-size: unit(34rem);
        }
        p{
          font-size: unit(22rem);
          color: $color999;
        }
      }
      .pic{
        margin-left: unit(30rem);
        width: unit(226rem);
        height: unit(162rem);
      }
      &:after{
        left: auto;
        right: 0;
        width: 96%;
      }
      &:last-child{
        &:after{
          display: none;
        }
      }
    }
  }
}
</style>
