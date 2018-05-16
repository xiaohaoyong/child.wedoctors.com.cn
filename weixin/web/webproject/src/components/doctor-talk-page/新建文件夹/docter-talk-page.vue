<template>
  <transition name="slide-left">
  <section class="talk_docter">
    <my-title>聊天</my-title>
    <div class="talk_box" ref="talk_scroller">
      <div class="fixed_childinfo">
          <group>
            <cell title="11" :link="{path:'/talk-forparent'}" inline-desc="9月1天" value="健康档案">
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
            'pic':item.type == 'pic'
          }">

          <span v-if="item.type == 'talk'" class="avatar">
            <img src="./avatar.png" v-if="item.qa == 'a'">
            <img src="./avatar-doctor.png" alt="" v-else>
          </span>
          <div v-if="item.type == 'talk'" class="text"><p>{{item.text}}<i class="angle"></i></p></div>

          <span v-if="item.type == 'pic'" class="avatar"><img src="./avatar-doctor.png" alt=""></span>
          <div v-if="item.type == 'pic'" class="text">
            <p><img :src="item.src" alt=""></p>
          </div>

          <time v-if="item.type == 'time'">{{item.time}}</time>
        </li>

      </ul>
    </div>
    <div class="input_box">
      <group class="input_area">
        <x-textarea v-model="speak" :max="200" :rows="1" placeholder="输入咨询问题" :show-counter="false" autosize></x-textarea>
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

  </section>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue';
  import { dateFormat } from 'vux'
  import { Cell, Group,XTextarea,XButton } from 'vux'
  import {httpChildType} from 'my_api/missions'
  export default {
    data() {
      return {
        speak: '',
        showtool: false,
        qa_list: [
          {
            'type': 'time',
            'time': '2017-10-18  下午13:51'
          },
          {
            'type': 'pic',
            'src': '/static/img/img.7fc589b.jpg'
          },
          {
            'type': 'talk',
            'qa': 'q',
            'text': '骚年，开始吧！'
          }
        ]
      }
    },
    created() {
//      this.fetch()
    },
    mounted() {
      let self = this;

      self.$nextTick(() => {
        self.$refs.upload.onchange = function () {//上传图片
          var file = this.files[0];
          var reader= new FileReader();
          reader.onload=function(){
            var url = reader.result;
            var img = new Image();
            img.src = url;
            console.log(url);

            self.qa_list.push({'type': 'pic','src': url});
            setTimeout(()=>{
              self.qa_list.push({'type': 'talk','qa': 'a','text': '别发黄图'});
              self.scrollBottom();
            },300)
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
      send() {//发送信息
        let self = this;
        self.qa_list.push({'type': 'talk','qa': 'q','text': self.speak});
        self.speak = '';
        setTimeout(()=>{
          self.qa_list.push({'type': 'talk','qa': 'a','text': '你再说一遍！'});
          self.scrollBottom();
        },300)
      },
      moreTool() {//展示发送图片
        let self = this;
        self.showtool = !self.showtool;
      },
      fetch() {
        let self = this;
        httpChildType().then((response) => {
          console.log(response);
          this.list = response.data;
        })
      },
      scrollBottom() {
        let self = this;
        self.$nextTick(() => {
          self.$refs.talk_scroller.scrollTop = self.$refs.talk_scroller.scrollHeight;
        });
      },
    },
    components: {
      myTitle,Cell, Group,XTextarea,XButton
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss">
.talk_docter{
  height: 100vh;
  display: flex;
  flex-direction: column;
  .talk_box{
    position: relative;
    padding-bottom: unit(20rem);
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
      .avatar{
        order: 2;
        width: unit(80rem);
        height: unit(80rem);
        img{
          display: block;
          width: 100%;
        }
      }
      .text{
        flex: 1;
        order: 1;
        width: 100px;
        p{
          float: right;
          border-radius: unit(8rem);
          overflow: hidden;
          margin-right: unit(40rem);
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
}
</style>
