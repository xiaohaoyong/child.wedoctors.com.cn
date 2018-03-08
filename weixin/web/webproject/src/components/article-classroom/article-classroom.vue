<template>
  <transition name="slide-left">
  <article class="article_classroom">
    <!--<my-title>{{title}}</my-title>-->
    <section class="content_wrapper" v-if="articles">
      <h2>{{articles.title}}</h2>
      <p class="desc"><span>{{articles.source}}</span>&nbsp;&nbsp;&nbsp;<span>{{articles.createtime*1000 | dateFormat('YYYY-MM-DD')}}</span></p>
      <!--<p class="desc">文章详情</p>-->
      <!--<div class="content">
		<video width="320" height="240" controls="controls" autoplay="autoplay">
		  <source src="./movie.mp4" type="video/mp4" />
		</video>
	  </div>-->
      <div class="content" :class="{add_padding: is_docter}" v-html="articles.content">
        <!-- <iframe width="100%" height="500" src="http://onrthfp25.bkt.clouddn.com/job_%E5%B0%8F%E5%84%BF%E7%94%9F%E7%90%86%E7%89%B9%E7%82%B9.mp4"></iframe> -->
        <!-- <p v-for="i in 4">一道电波穿越重霄，从首都北京传向辽阔大海。正在海军作战指挥大厅视察工作的习主席，在了解海军当日海空兵力活动情况后，临机与在海上执行战备巡逻任务的538舰视频通话，褒奖他们战风斗浪、连续奋战，勉励他们忠实履行职责，不负党和人民重托。</p> -->
        <!-- <img src="./article.png" alt=""> -->
        <!--<p>切个图上去？恩，不错，简单，兼容性也一级棒>不但好控制，那点小东西也增加不了多少图片的大小。但有没有更好更讲究技巧的办法呢？哈哈，那必须有啊，而且还不止一种呢：）</p>-->
        <!--<p>切个图上去？恩，不错，简单，兼容性也一级棒不但好控制，那点小东西也增加不了多少图片的大小。但有没有更好更讲究技巧的办法呢？哈哈，那必须有啊，而且还不止一种呢：）</p>-->
      </div>
    </section>
    <button class="send_btn" v-if="is_docter" @click="send_to_user">宣教发送</button>
  </article>
  </transition>
</template>

<script type="text/ecmascript-6">
  const ERR_OK = 200;
  import {httpArticle} from 'my_api/get';
  import { dateFormat } from 'vux'
  import myTitle from '../../base/title/title.vue'
    export default {
        data() {
            return {
              is_docter: false,
              articles: null,
            }
        },
        created() {
          this.fetch();
        },
      filters: {
        dateFormat: dateFormat
      },
        methods: {
          fetch() {

            let reg = /\/article\/(\d+)/;
            let index = this.$route.path.match(reg)[1];
            let self = this;

            httpArticle(index).then((response) => {
              if (response.code == ERR_OK){
                self.articles = response.data;
                console.log(self.articles);
                /*self.content = response.data.content;
                self.title = response.data.title;*/

                if (response.data.usertype == 0 && response.data.type == 0){//（只有医生usertype==0并且type==0的时候可以宣教）
                  self.is_docter = true
                }
              }else{
                self.$vux.toast.text('数据请求失败');
              }
            });
          },
          send_to_user() {
            let reg = /\/article\/(\d+)/;
            let index = this.$route.path.match(reg)[1];
            this.$router.push({
              'path': '/send-mission',
              query : {
                artid : index
              }
            })
          }
        },
        components: {myTitle}
    }
</script>
<style lang="scss" rel="stylesheet/scss" scoped>
  .article_classroom{
    overflow-x: hidden;
    width: 100%;
    .content_wrapper{
      padding: unit(50rem) unit(25rem) unit(20rem);
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
    }
    h2{
      padding: unit(10rem) 0;
      font-size: unit(44rem);
      font-weight: 700;
      color: #000;
    }
    .desc{
      padding: unit(20rem) 0;
      font-size: unit(30rem);
      color: #999;
    }
    .content{
      padding-bottom: 3.6rem;
      /*text-indent: 2em !important;*/
      &.add_padding{
        padding-bottom: 3rem;
      }
      margin-top: unit(40rem);
      line-height: unit(58rem);
      font-size: unit(34rem);
      color: #333;
      p{
        /*text-indent: 2em !important;*/
      }
      img{
        margin: unit(20rem) 0;
        display: block;
        width: 100%;
      }
    }
    .send_btn{
      position: fixed;
      display: block;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 3.185rem;
      border-width: 0;
      outline: 0;
      -webkit-appearance: none;
      margin-left: auto;
      margin-right: auto;
      padding-left: 14px;
      padding-right: 14px;
      box-sizing: border-box;
      font-size: 18px;
      text-align: center;
      text-decoration: none;
      color: #FFFFFF;
      line-height: 2.33333333;
      -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
      overflow: hidden;
      background-color: #e2705a;
    }
  }
</style>
<style lang="scss" rel="stylesheet/scss">
.article_classroom{
  overflow-x: hidden !important;
  .content{
    iframe{
      height: unit(400rem) !important;
    }
    img{
      margin: unit(20rem) 0;
      display: block;
      width: 100%;
    }
  }
}
</style>
