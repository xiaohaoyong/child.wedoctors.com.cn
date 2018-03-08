<template>
  <transition name="slide-left">
    <div class="docter_self">
      <my-title>{{title}}</my-title>

      <div class="infos">
        <img src="./avatar.png" alt="">
        <dl>
          <dt><b>{{myInfo.name}}</b><span>{{myInfo.titlename}}</span><span>{{myInfo.subject_bname}}</span></dt>
          <dd>{{myInfo.hospital}}</dd>
        </dl>
      </div>
      <dl class="desc" v-if="is_skilful">
        <dt>擅长</dt>
        <dd>{{myInfo.skilful}}
        </dd>
      </dl>
      <dl class="desc">
        <dt>简介</dt>
        <dd>{{myInfo.intro}}
        </dd>
      </dl>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import {XButton} from 'vux'
  import {httpDoctorinfo} from 'my_api/docter-info';

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

    .none_docter{
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      img{
        margin-top: unit(-100rem);
        width: unit(276rem);
        height: unit(284rem);
      }
      p{
        margin-top: unit(50rem);
        color: #bbbbbb;
        font-size: unit(36rem);
      }
    }
    .infos{//有医生的信息
      display: flex;
      align-items: center;
      padding: unit(55rem) unit(36rem) unit(36rem);
      background: url("./docterbg.jpg") no-repeat center;
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
      display: flex;
      margin-bottom: unit(32rem);
      padding: unit(32rem) unit(36rem) unit(110rem);
      background-color: #fff;
      dt{
        width: unit(200rem);
        font-size: unit(32rem);
        color: $color333;
        line-height: 1.3;
      }
      dd{
        font-size: unit(28rem);
        line-height: 1.5;
        color: $color999;
      }
    }
  }

</style>
