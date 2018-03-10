<template>
  <transition name="slide-left">
    <div class="add_docter">
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
          <dd>{{docterInfo.skilful}}
          </dd>
        </dl>
        <dl class="desc">
          <dt>简介</dt>
          <dd>{{docterInfo.intro}}
          </dd>
        </dl>
        <div class="control">
          <x-button type="primary" class="btn" v-if="!isPass">已提交申请，等待医生审核</x-button>
        </div>
        <button class="fixed-btn" v-if="isPass">已添加</button>
      </section>

    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import {XButton} from 'vux'
  import {httpAdddoctor,httpDoctorparent} from 'my_api/get';
  const ERR_OK = 200;
  export default {
    name: 'docter-info',
    data() {
      return {
        title: '添加医生',
        shanchang: true,
        use_avater: './avatar.png',
        docterInfo: [],
        hasdocter: true,
        isPass: true
      }
    },
    mounted() {
      this.title = this.$route.params.title ? this.$route.params.title : this.title;

      let self = this;
      let doctet_id = window.sessionStorage.getItem('dotor_id');
      console.log(doctet_id);
      if (doctet_id == null || doctet_id == undefined){//如果不带有doctet_id  null
        self.fetch();
      }else {//如果是带有doctet_id  null
        self.fetch_has(doctet_id);
      }
    },
    methods: {
      fetch_has(dotor_id) {
        let self = this;
        httpAdddoctor(dotor_id).then((response) => {//医生type0
          if (response.code == ERR_OK){//修改为判断后端返回值展示状态
            if (response.data){//有医生
              let lv =response.data.level;
              if (lv == '0'){
                self.hasdocter = true;
                self.isPass = false;
              } else if(lv == '1'){
                self.hasdocter = true;
                self.isPass = true;
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

        /*self.$axios.get('/parent/add-doctor',{params:{
          "doctor_id": dotor_id
        }}).then(function (response) {
          let data = response.data;

          if (data.code == ERR_OK){//修改为判断后端返回值展示状态
            if (data.data){//有医生
              let lv =data.data.level;
              if (lv == '0'){
                self.hasdocter = true;
                self.isPass = false;
              } else if(lv == '1'){
                self.hasdocter = true;
                self.isPass = true;
              } else if(lv == '-1'){
                self.hasdocter = false;
              }
              if(self.hasdocter){
                self.docterInfo = data.data;
                !self.docterInfo.skilful.length &&  (self.shanchang = false);
                !self.docterInfo.avatar.length &&  (self.use_avater = false);
              }
            }else {
              self.hasdocter = false;
            }
          }else{
            self.$vux.toast.text(data.msg);
          }

          /!*if (data.code == ERR_OK){ //直接执行已添加  修改为判断后端返回
            self.hasdocter = true;
            self.isPass = true;

            if(self.hasdocter){
              self.docterInfo = data.data;
              !self.docterInfo.skilful.length &&  (self.shanchang = false);
              !self.docterInfo.avatar.length &&  (self.use_avater = false);
            }
          }else{
            self.$vux.toast.text(data.msg);
          }*!/
        }).catch(function (error) {
          console.warn(error);
          self.$vux.toast.text('网络错误');
        });*/
      },
      fetch() {//没有带医生id
        let self = this;
        httpDoctorparent().then((response) => {//医生type0
          if (response.code == ERR_OK){
            if (response.data){//有医生
              let lv =response.data.level;
              if (lv == '0'){
                self.hasdocter = true;
                self.isPass = false;
              } else if(lv == '1'){
                self.hasdocter = true;
                self.isPass = true;
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

       /* self.$axios.get('/parent/doctor-parent').then(function (response) {
          let data = response.data;
          if (data.code == ERR_OK){
            if (data.data){//有医生
              let lv =data.data.level;
              if (lv == '0'){
                self.hasdocter = true;
                self.isPass = false;
              } else if(lv == '1'){
                self.hasdocter = true;
                self.isPass = true;
              } else if(lv == '-1'){
                self.hasdocter = false;
              }
              if(self.hasdocter){
                self.docterInfo = data.data;
                !self.docterInfo.skilful.length &&  (self.shanchang = false);
                !self.docterInfo.avatar.length &&  (self.use_avater = false);
              }
            }else {
              self.hasdocter = false;
            }
          }else{
            self.$vux.toast.text(data.msg);
          }
        }).catch(function (error) {
          console.warn(error);
          self.$vux.toast.text('网络错误');
        });*/
      }
    },
    components: {
      myTitle,XButton
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss">
  .add_docter{

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
