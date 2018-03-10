<template>
  <transition name="slide-left">
    <div class="setting">
      <my-title>设置</my-title>
      <group class="menus">
        <!--<cell title="修改密码" is-link :link="{path:'/setting-password',query: {usertype: 'parent'}}">
        </cell>-->
      </group>

      <div class="control_common">
        <x-button type="primary" class="btn" @click.native="login_out">退出登录</x-button>
      </div>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import { Cell,Group, XButton } from 'vux'
  import {httpLogout} from 'my_api/setting';
  const ERR_OK = 200;
    export default {
        data() {
            return {}
        },
        methods: {
          login_out() {
            let self = this;
            httpLogout().then((response) => {
              if (response.code == ERR_OK){
                self.$vux.toast.text(response.msg);
                setTimeout(()=>{
                  self.$router.push({
//                    path: `/account`
                    path: `/sign`
                  })
                },600)
              }else{
                self.$vux.toast.text(response.msg);
              }
            });
            /*self.$axios.post('/parent/logout').then(function (response) {
              let data = response.data;
              if (data.code == ERR_OK){
                self.$vux.toast.text(data.msg);
                setTimeout(()=>{
                  self.$router.push({
                    path: `/account`
                  })
                },600)
              }else{
                self.$vux.toast.text(data.msg);
              }
            }).catch(function (error) {
              console.warn(error);
              self.$vux.toast.text('网络错误');
            });*/
          }
        },
        components: {myTitle,Group,
          Cell,XButton}
    }
</script>

<style lang="scss" rel="stylesheet/scss">
  .setting{
    .menus{
      .weui-cells{@extend %weui_no_border;}
      .weui-cell{
        padding-top: unit(34rem);
        padding-bottom: unit(30rem);
      }
    }
  }
</style>
