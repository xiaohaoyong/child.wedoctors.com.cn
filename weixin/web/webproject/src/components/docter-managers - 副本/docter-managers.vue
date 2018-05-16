<template>
  <transition name="slide-left">
    <div class="docter_users_manager">
      <my-title>管辖儿童</my-title>
      <div class="wrapper">
        <tab :line-width=2 active-color='#f57961' :custom-bar-width="getBarWidth">
          <tab-item :selected="isType == 1" @on-item-click="onItemClick">已签约</tab-item>
          <tab-item :selected="isType == 0" @on-item-click="onItemClick">待签约</tab-item>
          <tab-item :selected="isType == -1" @on-item-click="onItemClick">未签约</tab-item>
        </tab>
        <section class="content" :class="{disableScroll: btn_screen}">
          <transition-group name="slide-in" tag="div">
            <nav class="header" v-show="0===showItems" :key="showItems">
              <div class="check_all" :class="{hide: btn_screen}">{{screen_age}}名儿童</div>
              <label class="screen" >
                <input type="checkbox" id="screen" v-model="btn_screen">
                年龄筛选
                <b class="iconbox">
                  <x-icon type="ios-arrow-right" class="icon" :class="{checked: btn_screen}" size="100%"></x-icon>
                </b>
              </label>

              <transition name="slide-slidedown">
                <div class="screen_wrapper" v-show="btn_screen">
                  <!--<checker v-model="screen_age" :max="5" type="checkbox" default-item-class="screen-default" selected-item-class="screen-selected">-->
                  <checker @on-change="toChange" v-model="screen_age" default-item-class="screen-default" selected-item-class="screen-selected">
                    <checker-item :value="item.value" class="screen_item" v-for="item in screen_data" :key="item.value">
                      {{item.text}}
                    </checker-item>
                  </checker><!--<span>{{screen_age}}</span>-->
                  <aside class="mask"></aside>
                </div>
              </transition>
            </nav>

            <div class="finshed" v-show="0===showItems" :key="showItems">
              <ul class="user_list" v-if="has_finshed">
                <li class="vux-1px-b" v-for="item in finshed">
                  <cell is-link :link="{path:'/record-fordocter',query: {'id': item.childid}}" value="">
                    <img slot="icon" src="./avatar.png">
                    <div slot="title">
                      <span class="name">{{item.name}}<i>{{item.age}}</i></span>
                      <span class="tel"><b class="icon"></b>{{item.phone | formatPhone}}</span>
                    </div>
                  </cell>
                </li>
                <load-more :show-loading="false" tip="到底了" background-color="#f8f8f8"></load-more>
              </ul>

              <div class="none_user" v-if="!has_finshed">
                <img src="./no-user.png" alt="">
                <p>
                  暂无已签约儿童，家长可以<br>
                  通过微信扫描<br>
                  我的设置 - 二维码，提交签约申请
                </p>
              </div>
            </div>

            <div class="wait" v-show="1===showItems" :key="showItems">
              <ul class="user_list" v-if="has_wait">
                <li class="vux-1px-b" v-for="item in wait">
                  <cell is-link :link="{path:'/waitsign-user',query: {'id': item.childid, 'type': isType}}" value="">
                    <img slot="icon" src="./avatar.png">
                    <div slot="title">
                      <span class="name">{{item.name}}<i>{{item.age}}</i></span>
                      <span class="tel"><b class="icon"></b>{{item.phone | formatPhone}}</span>
                    </div>
                  </cell>
                </li>
              </ul>
              <load-more v-if="!has_wait" :show-loading="false" tip="暂无待签约儿童" background-color="#f8f8f8"></load-more>
            </div>
            <div class="faild" v-show="2===showItems" :key="showItems">
              <ul class="user_list" v-if="has_faild">
                <li class="vux-1px-b" v-for="item in faild">
                  <cell value="">
                    <img slot="icon" src="./avatar.png">
                    <div slot="title">
                      <span class="name">{{item.name}}<i>{{item.age}}</i></span>
                      <span class="tel"><b class="icon"></b>{{item.phone | formatPhone}}</span>
                    </div>
                  </cell>
                </li>
              </ul>
              <load-more v-if="!has_faild" :show-loading="false" tip="暂无未签约儿童" background-color="#f8f8f8"></load-more>
            </div>
          </transition-group>
        </section>
      </div>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import { Checker, CheckerItem,Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem,Group,Cell,LoadMore } from 'vux'
  import {formatTel,formatText,formatArticleTime} from  '../../common/js/vaildform'
  import myChecks from '../../base/checks-icon/checks-icon.vue'
  import { httpManageUser } from 'my_api/get';
  import { httpChildType } from 'my_api/missions';

  import { querystring } from 'vux'
  const ERR_OK = 200;
  export default {
    data() {
      return {
        isType: 1,//展示类型
        finshed: [],//已签约
        wait: [],//待签约
        faild: [],//未签约
        index: 0,
        getBarWidth: function (index) {
          return  50 + 'px'
          return (index + 1) * 22 + 'px'
        },
        has_finshed: false,//有已签约的人员
        has_wait: false,//有已签约的人员
        has_faild: false,//有已签约的人员,
        btn_screen: false,//增加筛选按钮--- 已签约
        screen_age: 0,
        btn_check_all: true,
        screen_data: [

        ],
      }
    },
    watch: {
      /*screen_age(newVal,oldVal) {
        let self = this;
        console.log(newVal);
        self.fetch(1,newVal);
//          self.fetch(num);
        self.btn_screen = !self.btn_screen;
        return

      }*/
    },
    created() {
      this.fetchChildType()
//          console.log(this.$route.query);
      if (this.$route.query.p == 2){
        let self = this;
        this.isType = 0;
        self.onItemClick(1);
        return
//            this.showItems = 1;
      }
//          console.log(querystring.parse(('p=2'));
      this.fetch(this.isType)
    },
    computed: {
      showItems() {//改变显示的类型 控制

        return this.index;
      }
    },
    methods: {
      toChange (value){
        let self = this;
        self.fetch(1,value);
        self.btn_screen = !self.btn_screen;
        /*return;
        var promise = new Promise(function(resolve, reject){//异步监听全选按钮的选中状态
          if (newVal !== oldVal){
            resolve(newVal)
          }else {
            reject('您的手速太快了~~');
          }
        });
        promise.then((num) => {
          console.log(num);
          self.fetch(1,num);
//          self.fetch(num);
          self.btn_screen = !self.btn_screen;
        }, function(warn) {
          self.$vux.toast.text(warn, 'default');
        });*/
      },
      onItemClick (index) {
        let self = this;

        switch(index)
        {
          case 0:
            self.screen_age = 0;//恢复默认选中空
            self.btn_screen = false;//隐藏选框
            self.isType = 1;
            break;
          case 1:
            self.isType = 0;
            break;
          case 2:
            self.isType = -1;
            break;
          default :
            break;
        }

        self.fetch(self.isType);
//            console.log(self.isType);
//            console.log('on item click:', index)
        self.index = index;
      },
      fetchChildType() {//筛选的儿童列表
        let self = this;
        let _screen_data = [];

        httpChildType().then((response) => {
          let data = response.data;
          for (var key in data) {
            _screen_data.push({"value": key,"text": data[key].name})
          }
          self.screen_data = _screen_data;
        })
      },
      fetch(type,age) {
        let self = this;
        httpManageUser(type,age).then((response) => {
          if (response.code == ERR_OK){
            if (type == 1){//已签约
              self.finshed = response.data;
//                  console.log(self.finshed);
              (self.finshed.length) && (self.has_finshed = true);
            } else if(type == -1){//未签约
              self.faild = response.data;
              (self.faild.length) && (self.has_faild = true);
            } else if(type == 0){//待签约
              self.wait = response.data;
              (self.wait.length) && (self.has_wait = true);
            }
          }else{
            self.$vux.toast.text(response.msg);
          }
        });
        /*self.$axios.get('/doctor/child-have',{params: {
		  "type": type
		}}).then(function (response) {
		  let data = response.data;
		  if (data.code == ERR_OK){
			if (type == 1){//已签约
			  self.finshed = data.data;
//                  console.log(self.finshed);
			  (self.finshed.length) && (self.has_finshed = true);
			} else if(type == -1){//未签约
			  self.faild = data.data;
			  (self.faild.length) && (self.has_faild = true);
			} else if(type == 0){//待签约
			  self.wait = data.data;
			  (self.wait.length) && (self.has_wait = true);
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
    filters: {
      formatPhone: formatTel,
      formatArticle: formatText,
      formatArticleTime: formatArticleTime
    },
    components: {myTitle,Tab,myChecks,Checker, CheckerItem,
      TabItem,
      Sticky,
      Divider,
      XButton,
      Swiper,
      SwiperItem,Group,Cell,LoadMore}
  }
</script>

<style lang="scss" rel="stylesheet/scss">
  .docter_users_manager{
    .vux-tab{
      background-color: #f4f5f6;
    }
    .wrapper{
      height: 100vh;
      display: flex;
      flex-flow: column;
      .content{
        flex: 1;
        overflow-y: auto;
        &.disableScroll{
          overflow-y: hidden;
        }
        .header{
          z-index: 10;
          position: absolute;
          display: flex;
          width: 100%;
          padding: unit(20rem) unit(40rem);
          background-color: #fff;
          justify-content: space-between;
          color: $color999;
         /* .check_all{
            &.hide{
              visibility: hidden;
            }
          }*/
          .check_all,.screen{
            position: relative;
            padding: unit(4rem);
            font-size: unit(32rem);
            >input{
              opacity: 0;
              z-index:1;
              position: absolute;
              left: 0;
              top: 0;
              width: 100%;
              height: 100%;
            }
          }
          .screen{
            position: relative;
            .iconbox{
              vertical-align: middle;
              display: inline-block;
              width: unit(30rem);
              .icon{
                fill:#bbb;
                transition: transform 0.3s;
                &.checked{
                  transform:rotate(90deg);
                }
              }
            }
          }
          .screen_wrapper{
            position: absolute;
            width: 100%;
            left: 0;
            top: 100%;
            background-color: #f8fafa;
            color: $color999;
            text-align: center;
            .mask{
              content: '';
              opacity: 0.2;
              position: absolute;
              top: 100%;
              left: 0;
              width: 100%;
              height: 100vh;
              background-color: #000000;
            }
            .vux-checker-box{
              display: flex;
              flex-flow: wrap;
            }
            .screen_item{
              line-height: unit(80rem);
              font-size: unit(32rem);
              flex: 0 0 50%;
              &.screen-default{
                /*background-color: #f47961;*/
              }
              &.screen-selected{
                color: #fff;
                background-color: #f47961;
              }
            }
          }
        }
        .finshed{
          padding-top: unit(80rem);
          .none_user{
            text-align: center;
            width: 100%;
            padding: unit(260rem) unit(100rem) 0;
            img{
              margin-bottom: unit(50rem);
              width: unit(310rem);
              @extend %centerblock;
            }
            p{
              line-height: unit(48rem);
              color: #bbbbbb;
              font-size: unit(36rem);
            }
          }
        }
        .user_list{
          padding-top: unit(30rem);
          li{
            display: flex;
            background-color: #fff;
            &:after{
              left: auto;
              right: 0;
              width: 96%;
            }
            &:last-child{
              &:after{
                display: none;
              }
            }
          }
          .weui-cell{
            width: 100%;
            img{
              width: unit(146rem);
              margin-right: unit(34rem);
            }
            .vux-label{
              span{
                display: block;
                padding: unit(12rem) 0;
                color: #1a1b1f;
                &.name{
                  font-size: unit(32rem);
                  i{
                    padding-left: unit(22rem);
                    font-style: normal;
                    font-size: unit(26rem);
                  }
                }
                &.tel{
                  font-size: unit(24rem);
                  .icon{
                    display: inline-block;
                    margin-right: unit(14rem);
                    vertical-align: middle;
                    width: unit(32rem);
                    height: unit(32rem);
                    background: url("./icon-tel.png") no-repeat center / contain;
                  }
                }
              }
            }
            .weui-cell__ft{
              right: unit(30rem);
              font-size: unit(24rem);
              &:after {
                content: " ";
                display: inline-block;
                height: 6px;
                width: 6px;
                border-width: 2px 2px 0 0;
                border-color: #c0c0c0;
                border-style: solid;
                -webkit-transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);
                transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);
                position: relative;
                top: -2px;
                position: absolute;
                top: 50%;
                margin-top: -4px;
                right: unit(33rem);
              }
            }
          }
        }

      }
    }

  }
</style>
