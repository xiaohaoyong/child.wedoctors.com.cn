<template>
  <transition name="slide-left">
  <section class="parent_talk_list">
    <my-title>咨询解答</my-title>
    <!--<group class="item">
      <cell title="曹云等儿童的家长" :link="{path:'/talk-fordoctor',query: {'childid': 20}}" >
        <img slot="icon" src="./avatar.png" class="avatar">
      </cell>
    </group>-->
    <div v-if="list.length">
      <group class="item" v-for="(item,$index) in list" :key="$index">
        <cell :title="`${item.name}等儿童的家长`" :link="{path:'/talk-fordoctor',query: {'childid': item.id,'childname': item.name}}" >
          <img slot="icon" src="./avatar.png" class="avatar">
          <badge v-if="Number(item.message)"></badge>
        </cell>
      </group>
    </div>

    <div class="none_question" v-else>
      <p>暂无家长咨询</p>
    </div>
  </section>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue';
  import { Cell, Group, Badge } from 'vux'
  import { httpDoctorTalkList } from 'my_api/talk';
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
        httpDoctorTalkList().then((response) => {
          console.log(response);
          if (response.code == 200){
            self.list = response.data;
          }
        });

      }
    },
    components: {
      myTitle,Cell, Group,Badge
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
  .none_question{
      text-align: center;
      width: 100%;
      padding: unit(500rem) unit(100rem) 0;
      p{
        line-height: unit(48rem);
        color: #bbbbbb;
        font-size: unit(36rem);
      }
    }
}
</style>
