<template>
  <ul class="base-form_wrapper">
    <li class="item">
      <group>
        <x-input :title="formdata.texts.tel" name="mobile" placeholder="请输入手机号码" keyboard="tel" is-type=china-mobile v-model.trim="formdata.form.tel" :max="11"></x-input>
      </group>
    </li><!--
    2017-11.24 取消登录密码 输入
    <li class="item">
      <group>
        <x-input :title="formdata.texts.pw" name="mobile" placeholder="请输入8~18位登录密码" type="password" required v-model="formdata.form.pw" :max="18"></x-input>
      </group>
    </li>-->
    <li class="item">
      <group>
        <x-input :title="formdata.texts.verification" :max="4" class="weui-vcode" placeholder="请输入验证码" v-model="formdata.form.verification">
          <x-button slot="right" class="get_code" mini @click.native="getcode" :disabled="codedisbale">{{codebtn}}</x-button>
        </x-input>
      </group>
    </li>
  </ul>
</template>

<script type="text/ecmascript-6">
  import { XInput, Group, XButton, Cell } from 'vux'
  import * as Valide from  '../../common/js/vaildform'
  import {httpGetcode} from 'my_api/post';
  const ERR_OK = 200;
  const ERR_NO = 11001;


  export default {
    data() {
      return {
        codedisbale: false,
        codebtn: '获取验证码'
      }
    },
    props: {
      formdata: {
        type: Object
      },
      type: {
        default: 1
      }
    },
    methods: {
      getcode() {
        let self = this;
//        console.log(self.type);
        if (!Valide.isTel.apply(self,[self.formdata.form.tel])) return
        httpGetcode(self.formdata.form.tel,self.type).then((response) => {
          if (response.code == ERR_OK){
            self.$vux.toast.text(response.msg);
            let time = 60;
            let timer = null;
            self.codedisbale = true;
            timer = setInterval(()=>{
              self.codebtn = `${time}s重新发送`;
              time--;
              if (time < 0){
                clearTimeout(timer);
                self.codebtn = `获取验证码`;
                self.codedisbale = false;
              }
            },1000)
          } else if (response.code == ERR_NO){
            self.$vux.toast.text(response.msg);
          } else{
            self.$vux.toast.text('请重试');
          }
        });

      }
    },
    components: {
      XInput,
      XButton,
      Group,
      Cell
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss">
  .base-form_wrapper{
    .weui-btn_default:not(.weui-btn_disabled):active{
      background-color: $colorbg;
      color: #f57961;
      opacity: 0.7;
    }
    .weui-btn:after{
      display: none;
    }
    padding: unit(20rem) unit(50rem) 0;
    .item{
      .weui-label{
        width: unit(130rem) !important;
        color: $formtitle;
      }
      .weui-label,.weui-input{
        font-size: unit(34rem);
      }
      .weui-input{
        height: 1.4em;
      }
      .weui-cells{
        background-color: $colorbg;
        &:before{
          display: none;
        }
      }
    }
    .get_code{
      margin-right: -15px;
      color: #f57961;
      font-size: unit(28rem);
      &[disabled]{
        color: #999;
      }
      &:after{
        display: none;
      }
    }
  }
</style>
