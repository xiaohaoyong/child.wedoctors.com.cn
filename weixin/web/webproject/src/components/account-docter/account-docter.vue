<template>
  <transition name="slide-left">
    <div class="account_docter">
      <my-title>医生登录页</my-title>
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
      </div>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import { XInput, Group, XButton, Cell } from 'vux'
  import myTitle from '../../base/title/title.vue'
  import myFormsign from '../../base/form-account/form-account.vue'
  import * as Valide from  '../../common/js/vaildform'
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
            tel: '账号',
            pw: '密码'
          }
        }
      }
    },
    methods: {
      login() {
        let self = this;
        if (Valide.isTel.apply(self,[self.formdata.form.tel]) && Valide.isPw.apply(self,[self.formdata.form.pw])){
          let self = this;
          httpAccount(self.formdata.form.tel,self.formdata.form.pw,0).then((response) => {//医生type0
            if (response.code == ERR_OK){
              self.$vux.toast.text(response.msg);
              setTimeout(()=>{
                self.$router.push({
                  path: `/home-docter`
                })
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

<style scoped lang="scss" rel="stylesheet/scss">

  .account_docter{
    position: relative;
    height: 100vh;
    .control{
      padding: unit(90rem) unit(50rem) 0;
      text-align: center;
      .btn{
        border-radius: unit(90rem);
        height: unit(90rem);
      }
    }
  }

</style>
