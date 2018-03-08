<template>
  <transition name="slide-left">
    <div class="account">
      <my-title>家长登陆页</my-title>
      <my-formsign :formdata="formdata"></my-formsign>
      <!--<ul class="form_wrapper">-->
        <!--<li class="item">-->
          <!--<group title="">-->
            <!--<x-input title="账号" name="mobile" is-type=china-mobile placeholder="请输入手机号码" keyboard="number" mask="99999999999" v-model.trim="form.tel" :max="13"></x-input>-->
          <!--</group>-->
        <!--</li>-->
        <!--<li class="item">-->
          <!--<group title="">-->
            <!--<x-input title="密码" name="mobile" placeholder="请输入登录密码" type="password" keyboard="number" v-model="form.pw" :max="16"></x-input>-->
          <!--</group>-->
        <!--</li>-->
      <!--</ul>-->
      <div class="control">
        <x-button type="primary" class="btn" @click.native="login">登录</x-button>
        <p class="">
          <router-link to="/forget" class="account">忘记密码</router-link>
        </p>
      </div>
      <div class="sign">
        <router-link to="/sign" class="">注册</router-link>
      </div>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import { XInput, Group, XButton, Cell } from 'vux'
  import myTitle from '../../base/title/title.vue'
  import myFormsign from '../../base/form-account/form-account.vue'
  import * as Valide from  '../../common/js/vaildform'
  // import {httpAccount} from 'my_api/account';
  import {httpAccount} from 'my_api/post';

  const ERR_OK = 200;
  const ERR_NO = 11001;
  export default {
    name: 'hello',
    data() {
      return {
        formdata: {
          form: {
            tel: '',
            pw: ''
          },
          texts: {
            tel: '手机号',
            pw: '新密码'
          }
        }
      }
    },
    created() {
      if (this.$route.query.doctor_id){
        window.sessionStorage.setItem('doctor_id',this.$route.query.doctor_id)
      }
    },
    methods: {
      login() {
        let self = this;
        let doctet_id = window.sessionStorage.getItem('doctor_id');

        if (Valide.isTel.apply(self,[self.formdata.form.tel]) && Valide.isPw.apply(self,[self.formdata.form.pw])){
          let self = this;
          httpAccount(self.formdata.form.tel,self.formdata.form.pw,1).then((response) => {
            if (response.code == ERR_OK){
              self.$vux.toast.text(response.msg);
              setTimeout(()=>{
                if (doctet_id == null || doctet_id == undefined){//如果不带有doctet_id  null-- 跳转到用户首页
                  self.$router.push({
                    path: `/home-parent`
                  });
                }else {//如果不带有doctet_id  跳转到签约
                  self.$router.push({
                    path: `/add-docter`
                  });
                }
              },600)
            } else if (response.code == ERR_NO){
              self.$vux.toast.text(response.msg);
            } else{
              self.$vux.toast.text('请求失败');
            }
          });
        }
      }
    },
    components: {
      XInput,
      XButton,
      Group,
      Cell,
      myTitle,
      myFormsign
    }
  }
</script>

<!-- scoped添加私有样式  lang添加babel编译  rel添加IDE识别 -->
<style lang="scss" rel="stylesheet/scss">

  .account{
      /*@extend %centerblock;
      @include clearfix();
      background-color: $colorccc;
      background-repeat: no-repeat;
      @include bg-image('icon');*/
    position: relative;
    height: 100vh;
    .control{
      padding: unit(90rem) unit(50rem) 0;
      text-align: center;
      .btn{
        border-radius: unit(90rem);
        height: unit(90rem);
      }
      >p{
        padding-top: unit(60rem);
      }
      .account{
        font-size: unit(32rem);
        color: $colorbbb;
      }
    }
    .sign{
      position: absolute;
      bottom: unit(50rem);
      width: 100%;
      text-align: center;
      a{
        color: #f57961;
        font-size: unit(38rem);
      }
    }
  }

</style>
