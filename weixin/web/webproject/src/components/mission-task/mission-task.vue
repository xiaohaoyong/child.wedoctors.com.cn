<template>
  <transition name="slide-left">
  <section class="mission_task">
    <my-title>宣教任务</my-title>
    <group class="item" v-for="(item,$index) in list" :key="$index">
      <cell :title="item.name" :link="{path:'/docter-missiontask-send',query: {type: $index,finsh: item.unSendNum == '已完成' ? 0 : 1}}" :value="item.unSendNum"></cell>
    </group>
    <!--<group class="item">
      <cell title="满月" :link="{path:'/docter-missiontask-send'}" value="23"></cell>
    </group>
    <group class="item">
      <cell title="3月龄" :link="{path:'/docter-missiontask-send'}" value="已完成"></cell>
    </group>
    <group class="item">
      <cell title="6月龄" :link="{path:'/docter-missiontask-send'}" value="23"></cell>
    </group>
    <group class="item">
      <cell title="8月龄" :link="{path:'/docter-missiontask-send'}" value="23"></cell>
    </group>-->
  </section>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue';
  import { Cell, Group } from 'vux'
  import {httpChildType} from 'my_api/missions'
  export default {
    data() {
      return {
        list: []
      }
    },
    created() {
      this.fetch()
    },
    methods: {
      fetch() {
        let self = this;
        httpChildType().then((response) => {
          console.log(response);
          this.list = response.data;
        })
      }
    },
    components: {
      myTitle,Cell, Group
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss">
.mission_task{
  .item{
    @extend %weui_top_border;
    .weui-cell{
      padding-top: unit(26rem);
      padding-bottom: unit(26rem);
      &.vux-tap-active{
        &:active{
          position: relative;
          background-color: #fafafa;
          &:after{
            content: '';
            position: absolute;
            left: 0;
            width: unit(10rem);
            height: 100%;
            background-color: #f27860;
            border-radius: 0 unit(7rem) unit(7rem) 0;
          }
        }
      }
      .vux-cell-bd{
        font-size: unit(32rem);
        color: #333;
      }
      .weui-cell__ft{
        color: $colorbbb;
        font-size: unit(28rem);
      }
    }
  }
}
</style>
