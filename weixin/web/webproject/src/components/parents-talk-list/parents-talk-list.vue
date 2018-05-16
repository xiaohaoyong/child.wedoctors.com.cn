<template>
  <transition name="slide-left">
  <section class="parent_talk_list">
    <my-title>选择儿童身份</my-title>
    <!--<group class="item" v-for="(item,$index) in list" :key="$index">
      <cell :title="item.name" :link="{path:'/docter-missiontask-send',query: {type: $index,finsh: item.unSendNum == '已完成' ? 0 : 1}}" :value="item.unSendNum"></cell>
    </group>-->
    <!--<group class="item">
      <cell title="金宝宝" :link="{path:'/talk-forparent'}" inline-desc="9月1天" >
        <img slot="icon" src="./avatar.png" class="avatar">
      </cell>
    </group>-->
    <group class="item" v-for="(item,$index) in list" :key="$index">
      <cell :title="item.name" :link="{path:'/talk-forparent',query: {'touserid': touserid}}" :inline-desc="item.age" >
        <img slot="icon" src="./avatar.png" class="avatar">
      </cell>
    </group>
  </section>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue';
  import { Cell, Group } from 'vux'
  import {httpHomeparent} from 'my_api/get'
  export default {
    data() {
      return {
        list: [],
        touserid: ''

      }
    },
    created() {
      this.touserid = this.$route.query.userid;
      this.fetch()
    },
    methods: {
      fetch() {
        let self = this;
        httpHomeparent().then((response) => {
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
.parent_talk_list{
  .item{
    @extend %weui_top_border;
    .weui-cells:after{
      width: 80%;
    }
    .weui-cell{
      padding-top: unit(15rem);
      padding-bottom: unit(15rem);
      .avatar{
        margin-right: unit(30rem);
        width: unit(102rem);
      }
      .vux-cell-bd{
        >p{
          margin-top: unit(-10rem);
          margin-bottom: unit(-10rem);
        }
      }
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
