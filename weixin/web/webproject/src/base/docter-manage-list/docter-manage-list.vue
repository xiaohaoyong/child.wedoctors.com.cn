<template>
  <section class="doctor_manage_tab">
    <transition-group name="slide-in" tag="div" class="trans_group" :class="{disableScroll: btn_screen}">
      <nav class="header" v-show="1 == tabType && list.length || screen_age != 0" :key="22" >
        <div class="check_all" :class="{hide: btn_screen}">{{childs_num}}名儿童</div>
        <label class="screen" >
          <input type="checkbox" id="screen" v-model="btn_screen">
          {{screen_age > 0 ? screen_data[screen_age-1].text : '年龄筛选'}}
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
    </transition-group>

    <scroller class="user_list" :class="{'show-screen': 1 == tabType}" lock-x height="-44" @on-scroll-bottom="onScrollBottom" ref="scrollerBottom" :scroll-bottom-offst="100">
      <ul v-show="list.length">
        <li class="vux-1px-b" v-for="item in list" >
          <cell is-link :link="{path:'/record-fordocter',query: {'id': item.childid}}" value="">
          <!--<cell is-link :link="{path:'/record',query: {'id': item.childid}}" value="">-->
            <img slot="icon" src="./avatar.png">
            <div slot="title">
              <span class="name">{{item.name}}<i>{{item.age}}</i></span>
              <span class="tel"><b class="icon"></b>{{item.phone | formatPhone}}</span>
            </div>
          </cell>
        </li>
        <load-more :show-loading="false" tip="到底了" background-color="#f8f8f8"></load-more>
      </ul>

      <div class="none_user" v-if="!list.length">
        <p v-if="screen_age > 0">
          暂无儿童
        </p>

        <template v-else>
          <img src="./no-user.png" alt="">
          <p>
            暂无{{tabType == 1 ? '已' : '未'}}签约儿童，家长可以<br>
            通过微信扫描<br>
            我的设置 - 二维码，提交签约申请
          </p>
        </template>

      </div>

    </scroller>

  </section>
</template>

<script type="text/ecmascript-6">
//  import { Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem,Group,Cell,Scroller,LoadMore } from 'vux'
  import { Checker, CheckerItem,Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem,Group,Cell,LoadMore,Scroller } from 'vux'
  import {formatTel,formatText,formatArticleTime} from  '../../common/js/vaildform'
//import myChecks from './checks-icon/checks-icon.vue'
//console.log(myChecks);
import { httpManageUser } from 'my_api/get';
import { httpChildType } from 'my_api/missions';

const ERR_OK = 200;
  export default {
    props: {
      tabType: {
        default: null
      }
    },
    data() {
      return{
        btn_screen: false,//增加筛选按钮--- 已签约
        btn_check_all: true,
        screen_data: [

        ],
        active: 1,

        screen_age: 0,//初始的筛选
        childs_num: 0,//儿童人数

        onFetching: false,//正在加载状态
        loadstatus: true,
        contentList: 10,
        page: 1,
        search_age: '',
        list: []
      }
    },
    mounted() {
      this.fetchChildType(); //获取筛选
      this.fetch(this.tabType,this.search_age,this.page);
    },
    methods: {
      toChange (value){//重新筛选  -- 删除记录的年龄 页码  旧数据
        let self = this;
        self.search_age = value;
        self.list = [];
        self.page = 1;
        self.fetch(this.tabType,value,self.page);
        self.btn_screen = !self.btn_screen;
      },
      fetch(tabType,age,page) {
        let self = this;
        self.loadstatus = false;//加载的提示
        self.onFetching = false;//控制可以按需加载的开关
        httpManageUser(tabType,age,page).then((response) => {//医生type0
          if (response.code == ERR_OK){
            let lists = response.data.list;
            self.childs_num = response.data.num;
            if (lists) {
//            if (self.page<3){//暂时设置如果是第三页就无数据
              self.list = [...self.list,...lists];
              self.page++;
              self.$nextTick(() => {
                self.$refs.scrollerBottom.reset()
              })
              self.onFetching = true;
            }
          }
          self.loadstatus = true;
        });
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
      onScrollBottom() {
        if (!this.list.length) return;//如果首次加载就没有数据就不需要再做按需加载的处理
        if (this.onFetching) {
          this.fetch(this.tabType,this.search_age,this.page);
        }
      }
    },
    filters: {
      formatPhone: formatTel,
      formatArticle: formatText,
      formatArticleTime: formatArticleTime
    },
    components: {Checker, CheckerItem,Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem,Group,Cell,LoadMore,Scroller}
  }
</script>

<style lang="scss" rel="stylesheet/scss" >
  .doctor_manage_tab{
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    .trans_group{
      position: relative;
      .header{
        z-index: 10;
        position: absolute;
        display: flex;
        width: 100%;
        padding: unit(20rem) unit(40rem);
        background-color: #fff;
        justify-content: space-between;
        color: $color999;
        border-bottom: unit(3rem) solid #f8f8f8;
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
    }
    .user_list{
      flex: 1;
      &.show-screen{
        padding-top: unit(88rem);
      }
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
          /*right: unit(30rem);*/
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
</style>
