<template>
  <transition name="slide-left">
    <div class="record_card">
      <dl v-if="dataList">
        <dt>{{dataList.field82.value}}</dt>
        <dd v-for="(value,key,$index) in dataList" v-if="value.name && value.value">
          {{value.name}}：{{value.value}}
        </dd>
        <!--<dd>体重：12kg</dd>-->
        <!--<dd>头围：50cm</dd>-->
        <!--<dd>肥胖评价：无</dd>-->
        <!--<dd>营养不良评价：严重营养不良</dd>-->
        <!--<dd>身长：80cm</dd>-->
        <!--<dd>体检时间：2018年1月23日</dd>-->
      </dl>
      <my-empty v-else>
        <p slot="text">暂无体检数据</p>
      </my-empty>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import {httpParentChildExView} from "@/api/record"
  import myEmpty from '@/base/empty-list/empty-list.vue'


  export default {
    props: ['record'],
    data() {
      return {
        dataList: null
      }
    },
    created() {
      httpParentChildExView(this.record).then((res) => {
        this.dataList = res.data;
      }).catch((err) => {
        this.$vux.toast.text(err.msg);
      })
    },
    components: {
      myEmpty
    }
  }
</script>

<style scoped lang="scss" rel="stylesheet/scss">
  .record_card{
    min-height: 100vh;
    background-color: #fff;
    dl{
      padding: rem(36) rem(50);
    }
    dt{
      margin-bottom: rem(30);
      font-size: rem(48);
    }
    dd{
      line-height: rem(54);
      font-size: rem(28);
      color: #666666;
    }
  }
</style>
