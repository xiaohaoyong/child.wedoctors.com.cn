<template>
  <transition name="slide-left">
    <div class="forget">
      <my-title>忘记密码</my-title>
      <my-formsign :formdata="formdata" :type="2"></my-formsign>
      <!--<ul class="form_wrapper">-->
        <!--<li class="item">-->
          <!--<group title="">-->
            <!--<x-input title="手机号" name="mobile" placeholder="请输入手机号码" keyboard="number" mask="999 9999 9999" v-model="formdata.form.tel" :max="13"></x-input>-->
          <!--</group>-->
        <!--</li>-->
        <!--<li class="item">-->
          <!--<group title="">-->
            <!--<x-input title="新密码" name="mobile" placeholder="请输入登录密码" type="password" keyboard="number" v-model="formdata.form.pw" :max="16"></x-input>-->
          <!--</group>-->
        <!--</li>-->
        <!--<li class="item">-->
          <!--<group>-->
            <!--<x-input title="验证码" :max="4" class="weui-vcode" placeholder="请输入验证码" v-model="formdata.form.verification">-->
              <!--<x-button slot="right" class="get_code" mini >获取验证码</x-button>-->
            <!--</x-input>-->
          <!--</group>-->
        <!--</li>-->
      <!--</ul>-->
      <div class="control">
        <x-button type="primary" class="btn" @click.native="next">修改密码,并登录</x-button>
      </div>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import { XInput, Group, XButton, Cell } from 'vux'
  import myTitle from '../../base/title/title.vue'
  import myFormsign from '../../base/form-sign/form-sign.vue'
  import * as Valide from  '../../common/js/vaildform'
  import {httpUpdatepw} from 'my_api/post';
  const ERR_OK = 200;
  const ERR_NO = 11001;
  export default {
    name: 'hello',
    data() {
      return {
        formdata: {
          form: {
            tel: '',
            pw: '',
            verification: ''
          },
          texts: {
            tel: '手机号',
            pw: '新密码',
            verification: '验证码'
          }
        }
      }
    },
    methods: {
      next() {
        let self = this;
        if (Valide.isTel.apply(self,[self.formdata.form.tel]) && Valide.isPw.apply(self,[self.formdata.form.pw]) && Valide.isVerification.apply(self,[self.formdata.form.verification])){
          httpUpdatepw(self.formdata.form.tel,self.formdata.form.pw,self.formdata.form.verification).then((response) => {
            if (response.code == ERR_OK){
              self.$vux.toast.text(response.msg);
              setTimeout(()=>{
                self.$router.push({
                  path: `/home-parent`
                })
              },600)
            }else if(response.code == ERR_NO){
              self.$vux.toast.text(response.msg);
            }
          });
          /*self.$axios.post('site/update-password',{
            phone: self.formdata.form.tel,
            password: self.formdata.form.pw,
            verify: self.formdata.form.verification
          }).then(function (response) {
            let data = response.data;
            if (data.code == ERR_OK){
              self.$vux.toast.text(data.msg);
              setTimeout(()=>{
                self.$router.push({
                  path: `/infos`
                })
              },600)
            }else if(data.code == ERR_NO){
              self.$vux.toast.text(data.msg);
            }
          }).catch(function (error) {
            console.warn(error);
            self.$vux.toast.text('网络错误');
          });*/
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
  .forget{
      /*@extend %centerblock;
      @include clearfix();
      background-color: $colorccc;
      background-repeat: no-repeat;
      @include bg-image('icon');*/
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
  }

</style>
