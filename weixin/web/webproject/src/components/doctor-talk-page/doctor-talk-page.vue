<template>
  <transition name="slide-left">
    <section class="talk_doctor">
      <my-title>{{childName}}</my-title>
      <div class="talk_box" ref="talk_scroller">
        <div class="fixed_childinfo">
          <group>
            <cell :title="`家庭${familyChild}名儿童`" :link="{path:'/doctor-familylist-talk',query: {'id': this.$route.query.childid}}" value="健康档案">
              <img slot="icon" src="./avatar.png" class="avatar">
            </cell>
          </group>
        </div>


        <ul class="talk_list">
          <li v-for="item in qa_list"
            :class="{
              'talk':item.type == 'talk',
              'q':item.type == 'talk' && item.qa =='q',
              'a':item.type == 'talk' && item.qa =='a',

              'time':item.type == 'time',

              'pic':item.type == 'pic',
              'picr':item.type == 'pic' && item.qa =='q',
              'picl':item.type == 'pic' && item.qa =='a',
          }">

          <span v-if="item.type == 'talk'" class="avatar">
            <img src="./avatar.png" v-if="item.qa == 'a'">
            <img src="./avatar-doctor.png" alt="" v-else>
          </span>
            <div v-if="item.type == 'talk'" class="text"><p>{{item.text}}<i class="angle"></i></p></div>

            <!--<span v-if="item.type == 'pic'" class="avatar"><img src="./avatar-doctor.png" alt=""></span>
            <div v-if="item.type == 'pic'" class="text">
              <p><img :src="item.src" alt=""></p>
            </div>-->

            <span v-if="item.type == 'pic'" class="avatar">
              <img src="./avatar.png" alt="" v-if="item.qa == 'a'">
              <img src="./avatar-doctor.png" alt="" v-else>
            </span>
            <div v-if="item.type == 'pic'" class="text">
              <p><img :src="item.src" alt=""></p>
            </div>

            <time v-if="item.type == 'time'">{{item.time}}</time>
          </li>
        </ul>

      </div>
      <div class="input_box" ref="input_box">
        <group class="input_area">
          <x-textarea
            maxlength="150"
            @on-blur="ios11_show_block = false"
            @on-change="onInput"
            @on-focus="onInput"
            id="input_speak"
            v-model.trim="speak"
            :max="200"
            :rows="1"
            placeholder="输入咨询问题"
            :show-counter="false"
            autosize>
          </x-textarea>
        </group>
        <div class="menus">
          <x-button type="primary" class="submit" v-show="isInput" @click.native="send">发送</x-button>
          <a href="javascript:;" class="more" v-show="!isInput" @click="moreTool">
            <x-icon type="ios-plus-outline" size="30" class="icon" :class="{'active':showtool}"></x-icon>
          </a>
        </div>
        <transition name="slide-height">
          <ul class="tools" v-show="showtool">
            <li>
              <i class="icon photo"></i>
              <span class="desc">照片</span>
              <input type="file" accept="image/*" ref="upload">
            </li>
          </ul>
        </transition>
      </div>

      <div ref="ios11_block" class="ios11_block" v-show="ios11_show_block" :style="`height: ${ios11_block_height}px`"></div>
    </section>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue';
  import { dateFormat } from 'vux'
  import {getVersion} from  '../../common/js/vaildform'
  import { Cell, Group,XTextarea,XButton } from 'vux'
  import {httpDoctorTalkPageFamilyCount,httpTalkParent,httpTalkKeep} from 'my_api/talk'
  export default {
    data() {
      return {
        familyChild: 0,
        childName: '儿童家长',
        speak: '',
        showtool: false,
        saveTalk: [],

        ios11_block_height: 0,
        ios11_show_block: false,
        qa_list: [
          /*{
            'type': 'time',
            'time': dateFormat(new Date(), 'YYYY-MM-DD HH:mm:ss')
          },
          {
            'type': 'talk',
            'qa': 'a',
            'text': '肚子疼'
          },
          {
            'type': 'pic',
            'qa': 'q',
            'src': 'http://cms-bucket.nosdn.127.net/98618c46d2fc46ddb2010530ec9dcb2d20171025075350.jpeg?imageView&thumbnail=380y187&quality=85'
          },
          {
            'type': 'talk',
            'qa': 'q',
            'text': '阿西吧'
          },
          {
            'type': 'talk',
            'qa': 'a',
            'text': '头疼'
          }*/
        ]
      }
    },
    created() {
      this.childName = this.$route.query.childname + '的家长';

      this.userid = 0;
      this.touserid = this.$route.query.childid;
      this.getChildFamily(this.touserid);

      this.applyList();

      this.iosVersion11 = getVersion(11);//是否系统版本ios11

      this.init();
    },
    mounted() {
      let self = this;

      self.$nextTick(() => {

        /*ios11 bug 法克*/
        window.document.onkeydown = function () {//解决ios下原生键盘输入框被遮挡
          if(!self.speak.length){
            setTimeout(()=>{
              self.documentscrollBottom();
              self.scrollBottom();
            },100)
          }
        }
        document.querySelector('#input_speak textarea').onfocus = function () {//ios11键盘遮挡
          self.ios11();
        }
        self.$refs.input_box.addEventListener('touchmove', function (ev) {
          ev.preventDefault();
        }, false)
        self.$refs.ios11_block.addEventListener('touchmove', function (ev) {
          ev.preventDefault();
        }, false)

        /*上传图片*/
        self.$refs.upload.onchange = function () {
          var file = this.files[0];
          var reader= new FileReader();
          reader.onload=function(){
            var url = reader.result;
            var img = new Image();
            img.src = url;
            self.onInput();//隐藏上传按钮

            self.qa_list.push({'del': true,'type': 'pic','qa': 'q','src': 'data:image/gif;base64,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'});
            self.talk(self.touserid,url,1).then(()=>{// type=1 是图片
              self.qa_list.map((el,index)=>{
                if(el.del){
                  el.src = url;
                  el.del = false;
                }
              })
              self.scrollBottom();
            }).catch(()=>{
              let del_index = -1;
              self.qa_list.map((el,index)=>{
                if(el.del){
                  del_index = index
                }
              })
              self.qa_list.split(del_index,1);
              self.scrollBottom();
            });
          }
          reader.readAsDataURL(file);//写入图片
        }
      });
    },
    watch: {
      isInput() {
        if (this.isInput){
          this.showtool = false;
        }
      }
    },
    computed: {
      isInput() {
        return this.speak.length == 0 ? false : true;
      }
    },
    methods: {
      ios11() {
        if (this.iosVersion11) {//ios11下滚动条不受控制

          this.ios11_show_block = true;
          let clientH = document.documentElement.clientHeight;
          this.ios11_block_height = clientH/2;

          this.$nextTick(() => {
            this.$refs.talk_scroller.scrollTop = this.$refs.talk_scroller.scrollHeight;
            setTimeout(function () {
              document.body.scrollTop = document.documentElement.scrollTop = 0;
            }, 20)
          });
        }
      },
      init() {//ajax轮询获取数据
        this.timer = null;
        this.timer = setInterval(()=>{
          this.applyList();
        },3000)
      },
      applyList() {
        let self = this;
        self.getTalkList().then((res)=>{
//          console.log(res);
          if (res.code == 200){
            if (JSON.stringify(res.data) != JSON.stringify(self.saveTalk)){
              self.qa_list = [];

              self.saveTalk = res.data;
              self.dataPush(self.saveTalk);
            }
          }
        })
      },
      dataPush(data) {//渲染聊天
        let self = this;
        data.map((el)=>{
          if (el.userid){//如果是自己
            if (/^\[img\]/.test(el.userid)){
              self.qa_list.push({
                'type': 'pic',
                'qa': 'q',
                'src': el.userid.split('[img]')[1]
              })
            }else{
              self.qa_list.push({
                'type': 'talk',
                'qa': 'q',
                'text': el.userid
              })
            }
          }else{
            if (/^\[img\]/.test(el.touserid)){
              self.qa_list.push({
                'type': 'pic',
                'qa': 'a',
                'src': el.touserid.split('[img]')[1]
              })
            }else{
              self.qa_list.push({
                'type': 'talk',
                'qa': 'a',
                'text': el.touserid
              })
            }
          }
        })
        setTimeout(()=>{
          self.scrollBottom();
        },60)
      },
      getTalkList() {
        return httpTalkKeep(this.userid,this.touserid).then((response) => {
          return Promise.resolve(response);
        }).catch((err) => {
          return Promise.reject(err);
        });
      },
      getChildFamily(id) {
        httpDoctorTalkPageFamilyCount(id).then((response) => {
          if (response.code == 200){
            this.familyChild = response.data.count;
          }
        });
      },
      onInput() {
        this.showtool = false;
        setTimeout(()=>{
          this.documentscrollBottom();
          this.scrollBottom();
        },1000)
      },
      send() {//发送信息
        let self = this;
        if (!self.speak.length){
          self.$vux.toast.text('不可发送空内容');
        }
        self.qa_list.push({'type': 'talk','qa': 'q','text': self.speak});

        document.querySelector('#input_speak textarea').style.height = '24px';//输入完成之后input高度不自动回落
        self.talk(self.touserid,self.speak).then(()=>{//发送给后台数据
//          self.qa_list.push({'type': 'talk','qa': 'a','text': '请认真描述！'});

          self.scrollBottom();
        }).catch(()=>{
//          self.qa_list.push({'type': 'talk','qa': 'a','text': '用户信息获取错误，请联系管理员'});

          self.scrollBottom();
        });
      },
      talk(id,content,type) {
        let self = this;
        self.speak = '';
        return httpTalkParent(id,content,type).then((response) => {
          return Promise.resolve();
        }).catch(() => {
          return Promise.reject();
        })
      },
      moreTool() {//展示发送图片
        let self = this;
        self.showtool = !self.showtool;
      },
      scrollBottom() {
        let self = this;
        if (!self.iosVersion11) {//ios11下滚动条不受控制
          self.$nextTick(() => {
            self.$refs.talk_scroller.scrollTop = self.$refs.talk_scroller.scrollHeight;
          });
        }
      },
      documentscrollBottom() {//ios
        let self = this;
        if (!this.iosVersion11) {//ios11下滚动条不受控制
          self.$nextTick(() => {
            document.body.scrollTop = document.documentElement.scrollTop = document.documentElement.offsetHeight + self.$refs.input_box.getBoundingClientRect().height;
          });
        }
      }
    },
    components: {
      myTitle,Cell, Group,XTextarea,XButton
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss">
  .talk_doctor{
    height: 100vh;
    display: flex;
    flex-direction: column;
    overflow-y: hidden;
    .talk_box{
      // padding-bottom: unit(20rem);
      -webkit-overflow-scrolling: touch;
      overflow-x: hidden;
      overflow-y: auto;
      flex: 1;
      .fixed_childinfo{
        position: fixed;
        z-index: 100;
        top: 0;
        left: 0;
        width: 100%;
        height: unit(120rem);
        .weui-cells {
          margin-top: 0;
          background-color: rgba(#ffffff,0.5);
        }
        .avatar{
          margin-right: unit(30rem);
          width: unit(102rem);
        }
        .vux-cell-bd{
          >p{
            margin-top: unit(-10rem);
            margin-bottom: unit(-10rem);
          }
        }
      }
      .talk_list{
        padding: 0 unit(20rem);
        padding-top: unit(150rem);
      }
      .time{
        padding: unit(40rem) 0 unit(20rem);
        text-align: center;
        time{
          display: block;
          font-size: unit(24rem);
          color: $color999;
        }
      }
      .pic{
        padding: unit(40rem) 0 unit(20rem);
        display: flex;
        &.picr{//我方英雄
          .avatar{
            order: 2;
          }
          .text {
            order: 1;
            p{
              float: right;
              margin-right: unit(40rem);
            }
          }
        }
        &.picl{//敌方英雄
          .avatar{
            order: 1;
          }
          .text {
            order: 2;
            p{
              float: left;
              margin-left: unit(40rem);
            }
          }
        }
        .avatar{
          width: unit(80rem);
          height: unit(80rem);
          img{
            display: block;
            width: 100%;
          }
        }
        .text{
          flex: 1;
          width: 100px;
          p{
            border-radius: unit(8rem);
            overflow: hidden;
            width: unit(316rem);
            >img{
              display: block;
              width: 100%;
            }
          }
        }
      }
      .talk{
        margin-top: unit(30rem);
        display: flex;
        &.a{
          padding-right: unit(100rem);
          .text{
            p{
              float: left;
              background-color: #fff;
              border-radius: 0 unit(8rem) unit(8rem) unit(8rem);
              border: unit(1rem) solid #e8e8e8;
              color: #555555;
              .angle{
                position: absolute;
                overflow: hidden;
                top: unit(-1rem);
                left: unit(-16rem);
                width: unit(17rem);
                height: unit(25rem);
                background-color: #fff;
                border-top: unit(1rem) solid #e8e8e8;
                &:before{
                  content: '';
                  z-index: 1;
                  position: absolute;
                  top: unit(-1rem);
                  right: 0;
                  width: unit(80rem);
                  height: unit(130rem);
                  border-radius: unit(20rem);
                  border: unit(1rem) solid #e8e8e8;
                  background-color: #f8f8f8;
                }
              }
            }
          }
        }
        &.q{
          padding-left: unit(100rem);
          .avatar{
            order: 2;
          }
          .text{
            order: 1;
            p{
              float: right;
              background-color: #f47961;
              padding-left: unit(30rem);
              padding-right: unit(30rem);
              border-radius: unit(8rem) 0 unit(8rem) unit(8rem);
              border: unit(1rem) solid #d14e34;
              color: #fff;
              /*&:after{
				content: '';
				z-index: 1;
				position: absolute;
				top: unit(-1rem);
				right: unit(-16rem);
				width: unit(17rem);
				height: unit(25rem);
				background: url("./q-border.png") no-repeat left top / unit(17rem) unit(25rem);
			  }*/
              .angle{
                position: absolute;
                overflow: hidden;
                top: unit(-1rem);
                right: unit(-16rem);
                width: unit(17rem);
                height: unit(25rem);
                background-color: #f47961;
                border-top: unit(1rem) solid #d14e34;
                &:after{
                  content: '';
                  z-index: 1;
                  position: absolute;
                  top: unit(-1rem);
                  left: 0;
                  width: unit(80rem);
                  height: unit(130rem);
                  border-radius: unit(20rem);
                  border: unit(1rem) solid #d14e34;
                  background-color: #f8f8f8;
                }
              }
            }
          }
        }
        .avatar{
          width: unit(80rem);
          height: unit(80rem);
          img{
            display: block;
            width: 100%;
          }
        }
        .text{
          flex: 1;
          padding-left: unit(40rem);
          padding-right: unit(40rem);
          p{
            position: relative;
            padding: unit(28rem) unit(12rem) unit(28rem) unit(16rem);
            word-wrap: break-word;
            word-break: break-all;
          }
        }
      }
    }
    .input_box{
      min-height: unit(22rem);
      display: flex;
      flex-wrap: wrap;
      .input_area{
        flex:1;
        padding: unit(10rem) unit(16rem);
        .weui-cells{
          @extend %weui_no_border;
          margin-top:0;
          border-radius: unit(15rem);
          .weui-cell{
            padding-top: unit(10rem);
            padding-bottom: unit(10rem);
          }
        }
      }
      .menus{
        display: flex;
        padding-left: unit(15rem);
        padding-right: unit(30rem);
        padding-bottom: unit(8rem);
        align-items: flex-end;
        /*width: unit(150rem);*/
        .submit{
          line-height: unit(74rem);
          width: unit(121rem);
          height: unit(76rem);
          padding: 0;
        }
        .more{
          flex:1;
          height: 100%;
          display: flex;
          align-items: center;
          .icon{
            transition: 0.3s;
            &.active{
              transform: rotate(45deg);
            }
          }
          .vux-x-icon {
            fill: #83868c;
          }
        }
      }
      .tools{
        width: 100%;
        display: flex;
        text-align: center;
        padding: unit(20rem) unit(40rem);
        >li{
          margin-right: unit(30rem);
          position: relative;
          >input[type="file"]{
            position: absolute;
            z-index: 1;
            opacity: 0;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
          }
        }
        .icon{
          display: block;
          width: unit(81rem);
          height: unit(73rem);
          background-size: contain;
          background-repeat: no-repeat;
          background-position: center;
          @include bg-image("./photo");
        }
        .desc{
          display: block;
          margin-top: unit(18rem);
          font-size: unit(24rem);
          color: $color333;
        }
      }
    }
    .ios11_block{
      width: 100%;
      height: 0;
    }
  }
</style>
