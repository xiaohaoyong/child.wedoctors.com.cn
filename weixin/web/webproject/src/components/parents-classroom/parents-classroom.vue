<template>
  <div class="parents_classroom">
    <my-title>{{title}}</my-title>
    <div>
      <div class="hide_scrollbar" style="width: 100%;overflow-x:auto;-webkit-overflow-scrolling:touch;overflow-y:hidden;">
        <tab :line-width=0 active-color='#f57961' v-model="index" :custom-bar-width="getBarWidth">
          <tab-item class="vux-center" :selected="selectedType === item" v-for="(item, index) in navList" @click="selectedType = item" :key="index">{{item}}</tab-item>
        </tab>
      </div>

      <ul class="article_type_wrapper">
        <!--<li v-for="item in navList">{{item}}</li>-->
        <li v-for="(item,$index) in navList" v-show="index == $index">
          <my-artiles :catid="$index"></my-artiles>
        </li>
      </ul>

    </div>
  </div>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import myArtiles from '../../base/article-list/article-list.vue'
  import { Tab, TabItem } from 'vux'
  import {formatText} from  '../../common/js/vaildform'
  import {httpTabs,httpArticlelist} from 'my_api/classroom';
  const ERR_OK = 200;
  let THIS = null;
    export default {
        data() {
            return {
              title: '家长课堂',
              navList: [],
              selectedType: '推荐',
              artcileList: [],
              index: 0,
//              lists: ['111'],
              getBarWidth: function (index) {
                return  0 + 'px'
              }
            }
        },
        created() {
          if (this.$route.query.title === 'docter'){
            this.title = '预防宣传';
          }
          this.fetch();
          THIS = this;
        },
        watch: {
          'index'() {//监听切换列表
//            console.log(this.index);
          }
        },
      /*beforeRouteEnter (to, from, next) {
        next();
        if (THIS){//之后的执行
          if (THIS.$route.query.title === 'docter'){
//            THIS.title = '预防宣传';
          }else {
//            THIS.title = '家长课堂';
          }
        }else {//如果没有的话是首次
          var _timer = setInterval(()=>{//当前路由加载完成重复执行定时器直到开始轮询
            if (THIS){
              clearTimeout(_timer);//清除定时器
              if (THIS.$route.query.title === 'docter'){
//                THIS.title = '预防宣传';
              }else {
//                THIS.title = '家长课堂';
              }
            }
          },40)
        }
      },*/
        methods: {
          changeItem(index) {
            console.log(index);
          },
          fetch() {//渲染顶部的分类
            let self = this;
            httpTabs().then((response) => {
              if (response.code == ERR_OK){
                self.navList = response.data;
              }else{
                self.$vux.toast.text('数据请求失败');
              }
            });
            /*self.$axios.get('/parent/article-cate').then(function (response) {
              let data = response.data;
              if (data.code == ERR_OK){
                self.navList = data.data;
              }else{
                self.$vux.toast.text('数据请求失败');
              }
            }).catch(function (error) {
              console.warn(error);
              self.$vux.toast.text('网络错误');
            });*/
          },
          fetchContent() {
            let self = this;

            httpArticlelist(1,1).then((response) => {
              if (response.code == ERR_OK){
                self.artcileList[self.index] = response.data.list;
//                console.log(data.data.list);
              }else{
                self.$vux.toast.text('数据请求失败');
              }
            });

            /*self.$axios.get('/parent/article-list',{params: {
              page: 1,
              catid: 1
            }}).then(function (response) {
              let data = response.data;
//              console.log(data);
              if (data.code == ERR_OK){
                self.artcileList[self.index] = data.data.list;
//                console.log(data.data.list);
              }else{
                self.$vux.toast.text('数据请求失败');
              }
            }).catch(function (error) {
              console.warn(error);
              self.$vux.toast.text('网络错误');
            });*/
          },
          onScrollBottom() {}
        },
        filters: {
          formatArticle: formatText
        },
        components: {myTitle,Tab,
          TabItem,
          myArtiles}
    }
</script>

<style lang="scss" rel="stylesheet/scss">
.parents_classroom{

  >div{
    display: flex;
    flex-direction: column;
    height: 100vh;
    >.vux-slider{
      >.vux-swiper{

        .vux-swiper-item{
          padding-bottom: 60px;
        }
      }
    }
  }

  .vux-tab{
    position: relative;
    display: block;
    padding-left: unit(6rem);
    padding-right: unit(6rem);
    background-color: #f4f5f6;
    white-space: nowrap;
    &:after{
      position: absolute;
      content: '';
      left: 0;
      width: 100%;
      bottom: 0;
      right: 0;
      height: 1px;
      border-bottom: 1px solid #C7C7C7;
      color: #C7C7C7;
      transform-origin: 0 100%;
      transform: scaleY(0.5);
    }
    .vux-tab-item{
      display: inline-block;
      white-space: nowrap;
      width: auto;
      padding-left: unit(25rem);
      padding-right: unit(25rem);
      background: none;
      &.vux-tab-selected{
        position: relative;
        &:after{
          content: '';
          width: 50%;
          position: absolute;
          bottom: 0;
          left: 0;
          right: 0;
          margin: auto;
          height: 2px;
          background-color: #f67962;
        }
      }
    }
  }
  .article_type_wrapper{
    flex:1;
    li{
      height: 100%;
    }
  }

}
</style>
