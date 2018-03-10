<template>
  <ul class="base-form_wrapper">
    <li class="item">
      <group title="">
        <x-input :title="formdata.texts.tel" name="mobile" is-type=china-mobile placeholder="请输入手机号码" keyboard="tel" v-model.trim="formdata.form.tel" :max="11"></x-input>
      </group>
    </li>
    <li class="item">
      <group title="">
        <x-input :title="formdata.texts.pw" name="mobile" placeholder="请输入登录密码" type="password" keyboard="number" v-model="formdata.form.pw" :max="18"></x-input>
      </group>
    </li>
  </ul>
</template>

<script type="text/ecmascript-6">
  import { XInput, Group, XButton, Cell } from 'vux'
  export default {
    props: {
      formdata: {
        type: Object
      }
    },
    methods: {
      getcode() {
        console.log(this.formdata);
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

<!-- scoped添加私有样式  lang添加babel编译  rel添加IDE识别 -->
<style lang="scss" rel="stylesheet/scss">
  .base-form_wrapper{
    padding: unit(20rem) unit(50rem) 0;
    .weui-btn:after{
      display: none;
    }
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
  }
</style>
