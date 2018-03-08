<template>
  <article>
    <scroller class="mission_history" lock-x height="-44" @on-scroll-bottom="onScrollBottom" ref="scrollerBottom" :scroll-bottom-offst="100">
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
              <!--<img src="./article_img.png" class="pic">-->
              <img :src="item.img" class="pic">
            </router-link>
          </li>
          <!--<li class="vux-1px-b" v-for="i in 10">
            <a href="">
              <div class="desc">
                <h3>如果你无法简洁的表达你的
                  想法,那指说明你还不够了解
                  它   爱因斯坦
                </h3>
                <p>关于母乳喂养，刨宫妈妈要提问</p>
              </div>
              <img src="./article_img.png" class="pic">
            </a>
          </li>-->
        </ul>
        <load-more tip="正在加载" :class="{'hide_icon': loadstatus}"></load-more>
        <load-more :class="{'hide_icon': !loadstatus}" :show-loading="false" tip="我也是有底线的" background-color="#f8f8f8"></load-more>
      </div>
    </scroller>
  </article>
</template>

<script type="text/ecmascript-6">
  import { Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem,Group,Cell,Scroller,LoadMore } from 'vux'
  import {formatText} from  '../../common/js/vaildform'
  import {httpArticlelist} from 'my_api/classroom';
const ERR_OK = 200;
  export default {
    data() {
      return{
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

        httpArticlelist(nPage,self.catid).then((response) => {//医生type0
          if (response.code == ERR_OK){
            let lists = response.data.list;
            if (lists) {
//            if (self.page<3){//暂时设置如果是第三页就无数据
              self.list = [...self.list,...lists];
              self.page++;
              self.$nextTick(() => {
                self.$refs.scrollerBottom.reset()
              })
              self.onFetching = true;
            }
          }
          self.loadstatus = true;
        });
        /*self.$axios.get('/parent/article-list',{params: {
          page: nPage,
          catid: self.catid
        }}).then(function (response) {
          let data = response.data;
          if (data.code == ERR_OK) {
            let lists = data.data.list;
//            console.log(lists);
            if (lists) {
//            if (self.page<3){//暂时设置如果是第三页就无数据
              self.list = [...self.list,...lists];
//              console.log(self.list);
              self.page++;
              self.$nextTick(() => {
                self.$refs.scrollerBottom.reset()
              })
              self.onFetching = true;
            }
          }
          self.loadstatus = true;
        }).catch(function (error) {
          console.warn(error);
          self.$vux.toast.text('网络错误');
        });*/
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
    components: {Tab,
      TabItem,
      Sticky,
      Divider,
      XButton,
      Swiper,
      SwiperItem,Group,Cell,Scroller,LoadMore}
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
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
          font-size: unit(26rem);
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
