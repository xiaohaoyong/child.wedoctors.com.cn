<template>
  <article>
    <scroller class="mission_history" lock-x height="-50" @on-scroll-bottom="onScrollBottom" ref="scrollerBottom" :scroll-bottom-offst="100">
      <div>
        <ul class="tab-swiper vux-center articles_wrapper">
          <li class="vux-1px-b" v-for="item in list">
            <router-link :to="`/article/${item.id}`">
              <div class="desc">
                <h3>{{item.title}}
                </h3>
                <!--<p v-html="item.content"></p>-->
                <p>{{item.content | formatHTML | formatArticle}}</p>
              </div>
              <!--<img v-if="item.img" src="./article_img.png" class="pic">-->
              <img :src="item.img" class="pic">
            </router-link>
          </li>
        </ul>
        <load-more v-show="emptyList" :show-loading="false" tip="列表为空" background-color="#f8f8f8"></load-more>
        <load-more v-show="!emptyList" tip="正在加载" :class="{'hide_icon': loadstatus}"></load-more>
        <load-more v-show="!emptyList" :class="{'hide_icon': !loadstatus}" :show-loading="false" tip="我也是有底线的" background-color="#f8f8f8"></load-more>
      </div>
    </scroller>
  </article>
</template>

<script type="text/ecmascript-6">
  import {Scroller,LoadMore } from 'vux'
  import {formatText} from  '../../common/js/vaildform'
  import {httpUserRead} from 'my_api/missions'
const ERR_OK = 200;
  export default {
    data() {
      return{
        emptyList: false,
        onFetching: false,//正在加载状态
        loadstatus: true,
        contentList: 10,
        page: 1,
        list: []
      }
    },
    created() {
      this.fetch(this.page);
    },
    props: {
      catid: {
        default: 0
      }
    },
    methods: {
      fetch(nPage) {
        let self = this;
        self.loadstatus = false;//加载的提示
        self.onFetching = false;//控制可以按需加载的开关
        let type = (1 + Number(self.catid));
        httpUserRead(nPage,type).then((response) => {//type 1 未查看  type 2已查看
//          console.log(response);
          if (response.code == ERR_OK) {
            let lists = response.data.list;
//            console.log(lists);
            if(lists.length < 1){
              self.emptyList = true;
            }else {
              self.emptyList = false;
            }
            if (lists) {
//            if (self.page<3){//暂时设置如果是第三页就无数据
              self.list = [...self.list, ...lists];
              self.page++;
              self.$nextTick(() => {
                self.$refs.scrollerBottom.reset()
              })
              self.onFetching = true;
            }
            self.loadstatus = true;
          }
        })
//        self.$axios.get('/parent/article-list',{params: {
//          page: nPage,
//          catid: self.catid
//        }}).then(function (response) {
//          let data = response.data;
//          if (data.code == ERR_OK) {
//            let lists = data.data.list;
////            console.log(lists);
//            if (lists) {
////            if (self.page<3){//暂时设置如果是第三页就无数据
//              self.list = [...self.list,...lists];
////              console.log(self.list);
//              self.page++;
//              self.$nextTick(() => {
//                self.$refs.scrollerBottom.reset()
//              })
//              self.onFetching = true;
//            }
//          }
//          self.loadstatus = true;
//        }).catch(function (error) {
//          console.warn(error);
//          self.$vux.toast.text('网络错误');
//        });
      },
      onScrollBottom() {
        if (!this.list.length) return;//如果首次加载就没有数据就不需要再做按需加载的处理
        if (this.onFetching) {
          this.fetch(this.page)
        }
      }
    },
    filters: {
      formatArticle: formatText,
      formatHTML: function (str) {
        return str.replace(/<[^>]+>/g,"")
      }
    },
    components: {Scroller,LoadMore}
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
  .articles_wrapper{
    .hide_icon{
      backface-visibility:hidden;
    }
    overflow: hidden;
    li{
      &:first-child{
        margin-top: unit(30rem);
      }
      background-color: #fff;
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
          line-height: 1.15;
          color: #222;
          font-size: unit(34rem);
          overflow:hidden;
          -webkit-box-orient: vertical;
          text-overflow:ellipsis;
          -webkit-line-clamp: 3;
          display:-webkit-box;
        }
        p{
          text-overflow: ellipsis;
          /*white-space: nowrap;*/
          overflow:hidden;
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
</style>
