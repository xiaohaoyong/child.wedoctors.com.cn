<template>
  <transition name="slide-left">
    <div class="child_advisers">
      <my-title>{{title}}</my-title>

      <section class="has_docter">
        <div class="infos">
          <img src="./avatar.png">
          <!-- <img v-if="docterInfo.avatar" :src="docterInfo.avatar" alt="头像">
          <img v-else src="./avatar.png"> -->
          <dl>
            <dt><b>{{docterInfo.name}}</b><span>{{docterInfo.titlename}}</span><span>{{docterInfo.subject_bname}}</span></dt>
            <dd>{{docterInfo.hospital && docterInfo.hospital.name}}</dd>
          </dl>
        </div>
        <dl class="desc" v-if="shanchang">
          <dt>擅长</dt>
          <dd>
            <p>{{docterInfo.skilful}}</p>
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
        <p class="tips">为了更好的给您宝宝提供以上健康服务，请完善信息</p>
        <button class="fixed-btn" @click="next">签约成功，去完善信息&nbsp;&gt;&gt;</button>
      </section>

    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import {httpFirstQRDoctorparent} from 'my_api/get';
  const ERR_OK = 200;
  export default {
    name: 'docter-info',
    data() {
      return {
        title: '儿宝顾问',
        shanchang: true,
        use_avater: './avatar.png',
        docterInfo: [],
        hasdocter: true,
      }
    },
    created() {

    },
    mounted() {
      let self = this;
      if (this.$route.query.doctor_id){
        var userid = self.$route.query.doctor_id;//url获取用户id
        if (isNaN(userid)) {//如果是  qrscene_38 格式的
          userid = self.$route.query.doctor_id.split('_')[1];//url获取用户id
        }
        window.sessionStorage.setItem('doctor_id',userid)
      }
      self.fetch(userid);//如果不带有doctet_id  null
    },
    methods: {
      next() {
        this.$router.push({
          'path': '/sign',
          'query': {'isfirst': 1}//是否首次登录
        })
      },
      fetch(userid) {//没有带医生id
        let self = this;
        httpFirstQRDoctorparent(userid).then((response) => {//医生type0
          if (response.code == ERR_OK){
            if (response.data){//有医生
              let lv =response.data.level;
              if (lv == '0'){
                self.hasdocter = true;
              } else if(lv == '1'){
                self.hasdocter = true;
              } else if(lv == '-1'){
                self.hasdocter = false;
              }
              if(self.hasdocter){
                self.docterInfo = response.data;
                !self.docterInfo.skilful.length &&  (self.shanchang = false);
                !self.docterInfo.avatar.length &&  (self.use_avater = false);
              }
            }else {
              self.hasdocter = false;
            }
          }else{
            self.$vux.toast.text(response.msg);
          }
        });
      }
    },
    components: {
      myTitle
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped="">
  .child_advisers{

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
      background: url("./docterbg.png") no-repeat center;
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
    .tips{
      margin-top: unit(30rem);
      width: 100%;
      text-align: center;
      font-size: unit(26rem);
      color: $colorbbb;
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
