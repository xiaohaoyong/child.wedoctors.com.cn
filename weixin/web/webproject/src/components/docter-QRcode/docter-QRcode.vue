<template>
  <transition name="slide-left">
  <div class="QR_code">
    <my-title>获取二维码</my-title>
    <div class="box">
      <!--<img src="./qrcode.png" alt="">-->
      <img :src="src" alt="">
      <p>让家长微信扫一扫加我</p>
    </div>
  </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
    export default {
        data() {
            return {
              src: ''
            }
        },
        created() {
            let docterId = window.sessionStorage.getItem('QRcode_id');
            this.src = `http://we.child.wedoctors.com.cn/doctor/qrcode?id=${docterId}`;
        },
        methods: {},
        components: {myTitle}
    }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
  .QR_code{
    overflow: hidden;
    text-align: center;
    height: 100vh;
    background-color: #1c1c20;
    .box{
      @extend %centerblock;
      margin-top: unit(150rem);
      width: unit(600rem);
      padding: unit(40rem);
      background-color: #fff;
      border-radius: unit(13rem);
      img{
        display: block;
        width: 100%;
      }
      p{
        margin-top: unit(50rem);
        font-size: unit(34rem);
        color: #999999;
      }
    }
  }
</style>
