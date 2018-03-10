<template>
  <transition name="slide-left">
    <div class="docter_self">
      <my-title>{{title}}</my-title>

      <section class="has_docter">
        <div class="infos">
          <!--<img :src="use_avater" alt="">-->
          <!--<img :src="myInfo.avatar" alt="">-->
          <img src="./avatar.png" alt="">
          <dl>
            <dt><b>{{myInfo.name}}</b><span>{{myInfo.titlename}}</span><span>{{myInfo.subject_bname}}</span></dt>
            <dd>{{myInfo.hospital && myInfo.hospital.name}}</dd>
          </dl>
        </div>
        <dl class="desc">
          <dt>擅长</dt>
          <dd>
            <p>{{myInfo.skilful}}</p>
          </dd>
        </dl>
        <dl class="desc">
          <dt>服务事项</dt>
          <dd>
            <ul>
              <li>
                <i></i><span>健康咨询</span>
              </li>
              <li>
                <i></i><span>预防宣教</span>
              </li>
              <li>
                <i></i><span>体检通知</span>
              </li
              ><li>
              <i></i><span>疫苗通知</span>
            </li>
            </ul>
          </dd>
        </dl>
      </section>

    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import {XButton} from 'vux'
  import {httpDoctorinfo} from 'my_api/get';

const ERR_OK = 200;
  export default {
    name: 'docter-info',
    data() {
      return {
        title: '我的主页',
        is_skilful: false,
        myInfo: ''
      }
    },
    created() {
      let self = this;
      httpDoctorinfo().then((response) => {
        if (response.code == ERR_OK){
          self.myInfo = response.data;
          (response.data.skilful.length) && (self.is_skilful = true);
        }else{
          self.$vux.toast.text('请求失败');
        }
      });
      /*self.$axios.get('/doctor/doctor-view').then(function (response) {
        let data = response.data;
        if (data.code == ERR_OK){
          self.myInfo = data.data;
          (data.data.skilful.length) && (self.is_skilful = true);
        }else{
          self.$vux.toast.text('请求失败');
        }
      }).catch(function (error) {
        console.warn(error);
        self.$vux.toast.text('网络错误');
      });*/
    },
    mounted() {
      this.title = this.$route.params.title ? this.$route.params.title : this.title;
      this.title = this.$route.params.title ? this.$route.params.title : this.title;
//      console.log(this.$route.params.title);
    },
    methods: {},
    components: {
      myTitle,XButton
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
  .docter_self{
    .infos{//有医生的信息
      display: flex;
      align-items: center;
      padding: unit(55rem) unit(36rem) unit(36rem);
      background: #fff no-repeat center;
      background-size: cover;
      img{
        width: unit(130rem);
        height: unit(130rem);
      }
      dl{
        margin-left: unit(24rem);
        dt{
          margin-bottom: unit(18rem);
          b{
            padding-right: unit(22rem);
            font-size: unit(32rem);
            color: #000;
          }
          span{
            font-size: unit(24rem);
            color: #999;
            &:not(:last-child){
              position: relative;
              margin-right: unit(8rem);
              padding-right: unit(18rem);
              &::after{
                content: '|';
                position: absolute;
                right: 0;
                top: unit(3rem);
              }
            }
          }
        }
        dd{
          font-size: unit(24rem);
          color: #999999;
        }
      }
    }
    .desc{
      margin-top: unit(32rem);
      padding: unit(28rem) unit(33rem) unit(50rem);
      background-color: #fff;
      dt{
        padding-bottom: unit(20rem);
        width: unit(200rem);
        font-size: unit(32rem);
        color: $color333;
        line-height: 1.3;
      }
      dd{
        font-size: unit(30rem);
        line-height: 1.5;
        color: $color999;
        p{
          padding-right: unit(40rem);
        }
        ul{
          padding-top: unit(30rem);
          display: flex;
          justify-content: space-around;
          text-align: center;
          li{
            flex: 1;
            i{
              @extend %centerblock;
              display: block;
              margin-bottom: unit(16rem);
              width: unit(48rem);
              height: unit(48rem);
              background-size: contain;
              background-position: center center;
              background-repeat: no-repeat;
            }
            &:nth-of-type(1){
              i{@include bg-image("./icon1")}
            }
            &:nth-of-type(2){
              i{@include bg-image("./icon2")}
            }
            &:nth-of-type(3){
              i{@include bg-image("./icon3")}
            }
            &:nth-of-type(4){
              i{@include bg-image("./icon4")}
            }
          }
        }
      }
    }
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
