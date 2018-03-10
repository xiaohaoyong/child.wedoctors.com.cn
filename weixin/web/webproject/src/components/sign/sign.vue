<template>
  <transition name="slide-left">
    <div class="sign">
      <my-title>家长登录页</my-title>
      <my-formsign :formdata="formdata" :type="1"></my-formsign>
      <!--<ul class="form_wrapper">-->
        <!--<li class="item">-->
          <!--<group title="">-->
            <!--<x-input title="手机号" name="mobile" placeholder="请输入手机号码" keyboard="number" mask="999 9999 9999" v-model="form.tel" :max="13"></x-input>-->
          <!--</group>-->
        <!--</li>-->
        <!--<li class="item">-->
          <!--<group title="">-->
            <!--<x-input title="密码" name="mobile" placeholder="请输入登录密码" type="password" keyboard="number" v-model="form.pw" :max="16"></x-input>-->
          <!--</group>-->
        <!--</li>-->
        <!--<li class="item">-->
          <!--<group>-->
            <!--<x-input title="验证码" :max="4" class="weui-vcode" placeholder="请输入验证码" v-model="form.verification">-->
              <!--<x-button slot="right" class="get_code" mini >获取验证码</x-button>-->
            <!--</x-input>-->
          <!--</group>-->
        <!--</li>-->
      <!--</ul>-->
      <div class="control">
        <x-button type="primary" class="btn" @click.native="next" text="下一步"></x-button>
        <!--<p class="">
          &lt;!&ndash;2017-11.24 取消登录密码  删除入口&ndash;&gt;
          <router-link to="/account" class="account">已有账号去登录</router-link>
        </p>-->
      </div>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import { XInput, Group, XButton, Cell ,Loading} from 'vux'
  import myFormsign from '../../base/form-sign/form-sign.vue'
  import myTitle from '../../base/title/title.vue'
  import * as Valide from  '../../common/js/vaildform'
  import {httpSign} from 'my_api/post';
  const ERR_OK = 200;
  const ERR_NO = 11001;


  export default {
    name: 'sign',
    data() {
      return {
        doctor_id: '',
        formdata: {
          form: {
            tel: '',
//            pw: '',//2017-11.24 取消登录密码
            verification: ''
          },
          texts: {
            tel: '手机号',
//            pw: '密码',//2017-11.24 取消登录密码
            verification: '验证码'
          }
        },
      }
    },
    created() {
      if (this.$route.query.doctor_id){
        var userid = self.$route.query.doctor_id;//url获取用户id
        if (isNaN(userid)) {//如果是  qrscene_38 格式的
          userid = self.$route.query.doctor_id.split('_')[1];//url获取用户id
        }
        window.sessionStorage.setItem('doctor_id',userid)
      }
      this.doctor_id = window.sessionStorage.getItem('doctor_id')
//      this.$store.state.doctor_id = this.$route.query.doctor_id ? this.$route.query.doctor_id : this.$store.state.doctor_id;
//      console.log(this.$store.state.doctor_id);
    },
    methods: {
      next() {
        let self = this;
//      && Valide.isPw.apply(self,[self.formdata.form.pw])  2017-11.24 取消登录密码
        if (Valide.isTel.apply(self,[self.formdata.form.tel]) && Valide.isVerification.apply(self,[self.formdata.form.verification])){
          httpSign(self.formdata.form.tel,self.formdata.form.verification,self.doctor_id).then((response) => {
            if (response.code == ERR_OK){
              self.$vux.toast.text(response.msg);
              setTimeout(()=>{
                /*if (self.$route.query.isfirst == '1'){// isfirst ==1 首次登录是跳转至 后台转存页面
                  window.location = `http://we.child.wedoctors.com.cn/site/first-login-bydoctor?doctor_id=${self.doctor_id}`
                }else {//否则到家长首页
                  self.$router.push({
                    path: `/home-parent`
                  })
                }*/
                self.$router.push({
                  path: `/home-parent`
                })
              },600)
            }else if(response.code == ERR_NO){
              self.$vux.toast.text(response.msg);
            }
          });
          /*self.$axios.post('/site/reg',{
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
  .sign{
      /*@extend %centerblock;
      @include clearfix();
      background-color: $colorccc;
      background-repeat: no-repeat;
      @include bg-image('icon');*/
    /*.form_wrapper{
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
        .get_code{
          margin-right: -15px;
          color: #f57961;
          font-size: unit(28rem);
          &:after{
            display: none;
          }
          &:active,&:focus{
            opacity: 0.7;
            color: #f57961;
            background-color: $colorbg;
          }
          &[disabled]{
            opacity: 0.5;
          }
        }
      }
    }*/
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
