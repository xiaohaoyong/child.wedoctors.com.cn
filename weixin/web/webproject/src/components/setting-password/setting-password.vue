<template>
  <transition name="slide-left">
  <div class="change_password">
    <ul class="base-form_wrapper">
      <li class="item">
        <group>
          <x-input :title="formdata.texts.old" name="mobile" placeholder="请输入原密码" type="password" v-model.trim="formdata.form.oldpassword" :min="8" :max="18"></x-input>
        </group>
      </li>
      <li class="item">
        <group>
          <x-input :title="formdata.texts.new" name="mobile" placeholder="请输入新密码" type="password" v-model.trim="formdata.form.newpassword" :min="8" :max="18"></x-input>
        </group>
      </li>
      <li class="item">
        <group>
          <x-input :title="formdata.texts.valid_new" name="mobile" placeholder="确认新密码" type="password" :equal-with="formdata.form.newpassword" v-model.trim="formdata.form.conpassword" :min="8" :max="18"></x-input>
        </group>
      </li>
    </ul>
    <div class="control_common">
      <x-button type="primary" class="btn" @click.native="changePw">修改密码</x-button>
    </div>
  </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import { XInput, Group, XButton, Cell } from 'vux'
  import myTitle from '../../base/title/title.vue'
  import * as Valide from  '../../common/js/vaildform'
  import {httpUpdatepw} from 'my_api/setting';
  const ERR_OK = 200;
    export default {
      name: 'change-password',
        data() {
            return {
              userType: '',
              formdata: {
                form: {
                  oldpassword: '',
                  newpassword: '',
                  conpassword: ''
                },
                texts: {
                  old: '原密码',
                  new: '新密码',
                  valid_new: '确认输入'
                }
              }
            }
        },
      mounted() {
        this.userType = this.$route.query.usertype;
      },
        methods: {
          changePw() {
            let self = this;
            let password = self.formdata.form;
            if (!Valide.isPw.apply(self,[password.oldpassword,'请输入8~18位原密码']) || !Valide.isPw.apply(self,[password.newpassword,'请输入8~18位新密码']) || !Valide.isPw.apply(self,[password.conpassword,'请再次输入8~18位新密码'])){
              return
            }
            if (password.newpassword === password.conpassword){
              httpUpdatepw(password.oldpassword,password.newpassword,password.conpassword).then((response) => {
                if (response.code == ERR_OK){
                  if (self.userType){
                    self.$vux.toast.text(response.msg);
                  }else {
                    self.$vux.toast.text('页面异常，请重新进入');
                  }
                  setTimeout(()=>{
                    if (self.userType == 'docter'){
                      self.$router.push({
                        path: `/accountdocter`
                      })
                    } else if (self.userType == 'parent'){
                      self.$router.push({
                        path: `/account`
                      })
                    }else {
                      self.$router.go(-1);
                    }
                  },600)
                }else{
                  self.$vux.toast.text(response.msg);
                }
              });

              /*self.$axios.post('/parent/update-password',{
                oldpassword: password.oldpassword,
                newpassword: password.newpassword,
                conpassword: password.conpassword
              }).then(function (response) {
                let data = response.data;
//              console.log(data.data);
                if (data.code == ERR_OK){
                  if (self.userType){
                    self.$vux.toast.text(data.msg);
                  }else {
                    self.$vux.toast.text('页面异常，请重新进入');
                  }
                  setTimeout(()=>{
                    if (self.userType == 'docter'){
                      self.$router.push({
                        path: `/accountdocter`
                      })
                    } else if (self.userType == 'parent'){
                      self.$router.push({
                        path: `/account`
                      })
                    }else {
                      self.$router.go(-1);
                    }
                  },600)
                }else{
                  self.$vux.toast.text(data.msg);
                }
              }).catch(function (error) {
                console.warn(error);
                self.$vux.toast.text(data.msg);
              });*/
            }else {
              self.$vux.toast.text('两次输入的密码不一样');
            }
          }
        },
        components: {
          XInput,
          XButton,
          Group,
          Cell,
          myTitle
        }
    }
</script>

<style lang="scss" rel="stylesheet/scss" >
  .change_password{
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
          width: unit(170rem) !important;
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
          .weui-cell{
            padding-left: unit(8rem);
          }
          &:before{
            display: none;
          }
        }
      }
    }
  }
</style>
