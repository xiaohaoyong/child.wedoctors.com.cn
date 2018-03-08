<template>
  <transition name="slide-left">
  <section class="mission_task_send">
    <my-title>{{titletype}}儿童宣教</my-title>
    <group class="head">
      <cell :title="`${titletype}未宣教儿童有   ${child_num}名`">
        <img slot="icon" src="./avatar.png" alt="">
      </cell>
    </group>

    <div class="control_box">
      <h3 class="vux-1px-b">宣教发送基本内容</h3>
      <ul class="check_lists">
        <li v-for="(item,$index) in basicList" :key="$index">
          <!--<input type="checkbox" v-model="item.checked" disabled>-->
          <group class="item">
            <cell :title="item.content | formatArticle" :link="`/article/${item.id}`">
              <my-check slot="icon" :is-checked="item.checked"></my-check>
            </cell>
          </group>
        </li>
      </ul>
    </div>

    <div class="control_box">
      <h3 class="vux-1px-b">可选宣教内容</h3>
      <ul class="check_lists">
        <li v-for="(item,$index) in selectList" :key="$index">

          <group class="item">
            <cell :title="item.content | formatArticle" :link="`/article/${item.id}`">
              <span slot="icon" class="hide_check_btn_wrapper" v-on:click.stop="">
                <my-check slot="icon" :is-checked="item.checked"></my-check>
                <input class="hide_check_btn" type="checkbox" v-model="item.checked" :disabled="!send_show">
              </span>
            </cell>
          </group>
        </li>
      </ul>
    </div>
    <button class="fixed-btn" v-if="send_show" @click="send" :disabled="send_state">{{send_state ? '已发送' : `发送给${child_num}名儿童` }}</button>
  </section>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue';
  import myCheck from '../../base/checks-icon-mission/checks-icon-mission.vue';
  import {httpTypelistSend,httpArticlelistSend} from  'my_api/missions'
  import {formatText} from  '../../common/js/vaildform'
  import { Cell, Group } from 'vux'
  export default {
    data() {
      return {
        titletype: '',
        child_num: '',
        child_id: 0,
        send_state: false,
        send_show: false,
        basicList: [
          /*{
            checked: true,
            content: '啊啊啊'
          },
          {
            checked: true,
            content: '哦哦哦'
          },
          {
            checked: true,
            content: '呃呃呃'
          }*/
        ],
        selectList: [
          /*{
            checked: false,
            content: '啊1啊啊'
          },
          {
            checked: true,
            content: '哦3哦哦'
          },
          {
            checked: false,
            content: '呃2呃呃'
          }*/
        ]
      }
    },
    created() {
      let self = this;

      if (self.$route.query.finsh > 0){//如果是 1就是未完成  可以发送
        self.send_show = true;
      }

      self.child_id = self.$route.query.type;
      httpTypelistSend(self.child_id).then((response) => {
        console.log(response);
        if (response.code == 200){
          let data = response.data;

          self.titletype = data.child_type;
          self.child_num =  data.unsend;
          let _basicList = data.list;
          _basicList.map((el) => {
            self.basicList.push({'checked':true, 'content': el.title, 'id': el.id})
          })
//console.log(data.list1[1]);
          let _selectList = data.list1;
          _selectList.map((el) => {
            self.selectList.push({'checked':false, 'content': el.title, 'id': el.id})
          })
        }
      })
    },
    methods: {
      send(){
        let self = this;
        let postArticle = {};
//        console.log(self.basicList,self.selectList);
        self.basicList.map((el) => {
          if (el.checked){
            postArticle[el.id] = el.id;
          }
        });
        self.selectList.map((el) => {
          if (el.checked){
            postArticle[el.id] = el.id;
          }
        });

        httpArticlelistSend(self.child_id,postArticle).then((response) => {
          console.log(response);
          if (response.code == 200){
            self.$vux.toast.text('发送成功');
            self.send_state = true;
          }
        })

      }
    },
    filters: {
      formatArticle: formatText
    },
    components: {
      myTitle,Cell, Group,myCheck
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss">
.mission_task_send{
  padding-bottom: 4rem;
  .fixed-btn{
    z-index: 11;
  }
  .head{
    img{
      margin-right: unit(30rem);
      width: unit(83rem);
      height: unit(83rem);
    }
    .weui-cells{
      margin-top:0;
      @extend %weui_no_border;
    }
    .weui-cell{

      /*padding: unit(25rem) unit(32rem);*/
    }
  }

  .control_box{
    margin-top: unit(32rem);
    background-color: #fff;
    h3{
      text-indent: 4%;
      font-size: unit(32rem);
      padding: unit(48rem) 0;
      color: $color999;
      &:after{
        left: auto;
        right: 0;
        width: 96%;
      }
    }
    .check_lists{
      padding: unit(34rem) 0;
      li{
        position: relative;
        .hide_check_btn_wrapper{
          position: relative;
          .icon_box{
            vertical-align: middle;
          }
        }
        .hide_check_btn{
          z-index: 1;
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          opacity: 0;
        }
      }
    }
    .item{
      .weui-cells{@extend %weui_no_border;  margin-top:0;}
      .weui-cell{
        padding-top: unit(24rem);
        padding-bottom: unit(24rem);
        .weui-cell__hd{
          display: flex;
          position: relative;
        }
      }
    }
  }

}
</style>
