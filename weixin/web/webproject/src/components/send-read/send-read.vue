<template>
  <div class="mission_send">
    <my-title>健康指导文章</my-title>
    <tab class="nav" :line-width="3" active-color='#f27860' :custom-bar-width="getBarWidth">
      <tab-item selected @on-item-click="onItemClick">未查看</tab-item>
      <tab-item @on-item-click="onItemClick">已查看</tab-item>
    </tab>

    <div v-for="(item,$index) in 2" v-show="index == $index">
      <my-article :catid="$index"></my-article>
    </div>
  </div>
</template>

<script type="text/ecmascript-6">
  import { Tab, TabItem} from 'vux'
  import myTitle from '../../base/title/title.vue'
  import myArticle from '../../base/docter-article-read/docter-article-read.vue'
    export default {
      data() {
          return {
            index: 0,
            getBarWidth: function (index) {
              return  60 + 'px'
            }
          }
      },
      created(){
      },
      methods: {
        onItemClick(index){
          this.index = index;
        }
      },
      components: {myTitle,myArticle,Tab, TabItem}
    }
</script>

<style lang="scss" rel="stylesheet/scss">
.mission_send{
  .nav{
    /*margin-bottom: unit(30rem);*/
    height: unit(100rem);
    .vux-tab-item{
      line-height: unit(106rem);
      background: none;
    }
  }
}
</style>
