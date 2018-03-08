<template>
  <transition name="slide-left">
    <div class="child_advisers">
      <my-title>{{title}}</my-title>

      <section class="none_docter" v-if="!hasdocter">
        <img src="./qr-tip.png" alt="">
        <p>微信扫一扫医生二维码添加</p>
      </section>

      <section class="has_docter" v-if="hasdocter">
        <div class="infos">
          <!--<img :src="use_avater" alt="">-->
          <!--<img :src="docterInfo.avatar" alt="">-->
          <img src="./avatar.png" alt="">
          <dl>
            <dt><b>{{docterInfo.name}}</b><span>{{docterInfo.titlename}}</span><span>{{docterInfo.subject_bname}}</span></dt>
            <dd>{{docterInfo.hospital && docterInfo.hospital.name}}</dd>
          </dl>
        </div>
        <dl class="desc" v-if="shanchang">
          <dt>擅长</dt>
          <dd>
            <!-- <p>中医儿童健康管理，养护等2008年毕业于中医药 大学 </p> -->
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
        <button class="fixed-btn" disabled="true">暂未开通服务</button>
        <!-- <button class="fixed-btn" @click="next">立即咨询</button> -->
      </section>

    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import {httpAdddoctor,httpDoctorparent} from 'my_api/get';
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
    mounted() {
      let self = this;
      self.fetch();//如果不带有doctet_id  null
    },
    methods: {
      next() {
        this.$router.push({
          'path': '/talk-forparent',
          'query': {'touserid': this.docterInfo.userid,'doctor':this.docterInfo.name}
        })
      },
      fetch() {//没有带医生id
        let self = this;
        httpDoctorparent().then((response) => {//医生type0
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
      // background: url("./docterbg.png") no-repeat center;
      background-color: #fff;
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
