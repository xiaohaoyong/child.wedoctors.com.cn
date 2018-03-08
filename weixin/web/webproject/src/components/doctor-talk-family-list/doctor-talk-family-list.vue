<template>
  <transition name="slide-left">
  <section class="parent_talk_list">
    <my-title>咨询解答</my-title>
    <!--<group class="item" v-for="(item,$index) in list" :key="$index">
      <cell :title="item.name" :link="{path:'/docter-missiontask-send',query: {type: $index,finsh: item.unSendNum == '已完成' ? 0 : 1}}" :value="item.unSendNum"></cell>
    </group>-->
    <group class="item">
      <cell :key="item.id" v-for="item in list" :title="item.name" :link="{path:'/record-fordocter',query: {'id': item.id}}" :inline-desc="item.age">
        <img slot="icon" src="./avatar.png" class="avatar">
      </cell>
      <!--<cell title="曹云" :link="{path:'/record-fordocter',query: {'id': 20}}" :inline-desc="`2个月2天`">
        <img slot="icon" src="./avatar.png" class="avatar">
      </cell>-->
    </group>
    <!--<group class="item" v-for="(item,$index) in 5" :key="item">
      <cell title="曹云" :link="{path:'/talk-fordoctor',query: {'childid': 20}}" :inline-desc="`${$index+6}个月${$index+1}天`" >
        <img slot="icon" src="./avatar.png" class="avatar">
      </cell>
    </group>-->
  </section>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue';
  import { Cell, Group } from 'vux'
  import { httpDoctorFamilyList } from 'my_api/talk';
  export default {
    data() {
      return {
        list: []
      }
    },
    created() {
      this.fetch(this.$route.query.id)
    },
    methods: {
      fetch(childid) {
        let self = this;
        httpDoctorFamilyList(childid).then((response) => {
          if (response.code == 200){
            self.list = response.data
          }
        });
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
