<template>
  <div class="send_mission">
    <my-title>宣教发送</my-title>
    <nav class="header">
      <label for="checkall" class="check_all" :class="{hide: btn_screen}">
        <input type="checkbox" id="checkall" v-model="btn_check_all" @click="check_all">
        <my-checks :is-checked="btn_check_all"></my-checks>
        <span>全部</span>
      </label>
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
          <checker v-model="screen_age" default-item-class="screen-default" selected-item-class="screen-selected">
            <checker-item :value="item.value" class="screen_item" v-for="item in screen_data" :key="item.value">
              {{item.text}}
            </checker-item>
          </checker><!--<span>{{screen_age}}</span>-->
        </div>
      </transition>
    </nav>

    <ul class="user_list" v-show="has_list">
      <li class="vux-1px-b" v-for="(item,$index) in checkList">
        <input type="checkbox" v-model.lazy="item.checked" :key="$index" >
        <my-checks :is-checked="item.checked"></my-checks>
        <cell>
          <img slot="icon" src="./avatar.png">
          <div slot="title">
            <span class="name">{{item.name}}<i>{{item.age}}</i></span>
            <span class="tel"><b class="icon"></b>{{item.phone | formatPhone}}</span>
          </div>
        </cell>
      </li>
    </ul>

    <load-more v-show="!has_list" :show-loading="false" tip="暂无数据" background-color="#f8f8f8"></load-more>

    <button class="fixed-btn" @click="send">发送</button>
  </div>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import myChecks from '../../base/checks-icon/checks-icon.vue'
  import { Checker, CheckerItem,Cell, Flexbox, FlexboxItem,LoadMore } from 'vux'
  import {formatTel} from  '../../common/js/vaildform'
  import {httpBackchildlist,httpSendteach,httpChildType} from 'my_api/missions';

const ERR_OK = 200;
  const ERR_NO = 11001;
    export default {
        data() {
            return {
              has_list: false,
              btn_check_all: false,
              btn_screen: false,
//              screen_default: 0,
              screen_age: 0,
              screen_data: [
                {"value": "1", "text" :"0 ~ 3月"},
                {"value": "2", "text" :"3 ~ 6月"},
                {"value": "3", "text" :"6 ~ 12月"},
                {"value": "4", "text" :"12 ~ 18月"},
                {"value": "5", "text" :"18 ~ 24月"},
                {"value": "6", "text" :"24 ~ 30月"},
                {"value": "7", "text" :"30 ~ 36月"},
                {"value": "8", "text" :"3岁 ~ 6岁"}
              ],
              checkListControl: [false,false,false,true],
              checkList: [
                {
                  checked: false,
                  content: '啦啦啦1'
                },
                {
                  checked: false,
                  content: '啦啦啦2'
                },
                {
                  checked: false,
                  content: '啦啦啦3'
                }
              ]
            }
        },
        watch: {

          screen_age(newVal,oldVal) {
            let self = this;
            var promise = new Promise(function(resolve, reject){//异步监听全选按钮的选中状态
              setTimeout(()=>{
                if (newVal !== oldVal){
//                  console.log(newVal);
                  resolve(newVal)
                }else {
                  reject('您的手速太快了~~');
                }
              },50)
            });
            promise.then((num) => {
              self.checkControl(false);
              self.fetch(num);
              self.btn_screen = !self.btn_screen;
            }, function(warn) {
              self.$vux.toast.text(warn, 'default');
            });
          },
          btn_check_all() {
//            console.log(this.btn_check_all);
//            this.checkControl(this.btn_check_all)
          },
          checkList: {//深度监听
            handler: function (val, oldVal) {
              let self = this;
              let isCheckAll = true;
              for (var i = 0; i < val.length; i++) {
//                console.log(`新的${val[i].checked}    旧的${oldVal[i].checked}`);
                if (!val[i].checked){
                  isCheckAll = false;
                  break;
                }
              }
              self.btn_check_all = isCheckAll;
            },
            deep: true
          }
        },
        created() {
          this.fetch(this.screen_age);
          this.fetchChildType()
        },
        methods: {
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
          fetch(num) {
            let self = this;

            httpBackchildlist(num).then((response) => {
              if (response.code == ERR_OK){
                self.has_list = true;
                let _childList = response.data;
                _childList.map((el) => {
                  el.checked = false;
                });
                self.checkList = _childList
              }else if (response.code == ERR_NO){
                self.has_list = false;
              }else{
                self.$vux.toast.text('请求失败');
              }
            });
            /*self.$axios.get('/doctor/backchild-list',{params: {
              "num": num
            }}).then(function (response) {
              let data = response.data;
//              console.log(data.data);
              if (data.code == ERR_OK){
                self.has_list = true;
                let _childList = data.data;
                _childList.map((el) => {
                  el.checked = false;
                });
                self.checkList = _childList
              }else if (data.code == ERR_NO){
                self.has_list = false;
              }else{
                self.$vux.toast.text('请求失败');
              }
            }).catch(function (error) {
              console.warn(error);
              self.$vux.toast.text('网络错误');
            });*/
          },
          check_all() {//全选
            let self = this;
            function myPromise() {//通过Promise回调传值
              var promise = new Promise(function(resolve, reject){//异步监听全选按钮的选中状态
                setTimeout(()=>{
                  if (true){
                    resolve(self.btn_check_all);
                  }else {
                    reject('您的手速太快了~~');
                  }
                },50)
                 /* self.$watch('btn_check_all',function (newVal,oldVal) { //***在点击之后才监听不可用
                    if (newVal !== oldVal){
                      resolve(newVal);
                    }else {
                      reject('您的手速太快了~~');
                    }
                  });*/

              });
              return promise;
            }
            myPromise().then((isChecked) => {
              self.checkControl(isChecked)
            }, function(warn) {
              self.$vux.toast.text(warn, 'default');
            });
          },
          checkControl(isCheck){
            let self = this;
            self.checkList.map((el) => {
              el.checked = isCheck;
            })
          },
          send() {
            let self = this;
            let checkList = self.checkList;
            let _sendData = {};
            checkList.map((el) => {
              if(el.checked){
                _sendData[el.childid] = el.childid;
              }
            });

            httpSendteach(self.$route.query.artid,_sendData).then((response) => {
              if (response.code == ERR_OK){
                self.$vux.toast.text(response.msg);
                setTimeout(()=>{
                  self.$router.go(-1);
                },600)
              }else{
                self.$vux.toast.text('发送失败');
              }
            });
            /*self.$axios.post('/doctor/send-teach',{
              artid: self.$route.query.artid,
              childid: _sendData
            }).then(function (response) {
              let data = response.data;
              if (data.code == ERR_OK){
                self.$vux.toast.text(data.msg);
                setTimeout(()=>{
                  self.$router.go(-1);
                },600)
              }else{
                self.$vux.toast.text('发送失败');
              }
            }).catch(function (error) {
              console.warn(error);
              self.$vux.toast.text('网络错误');
            });*/
          }
        },
        filters: {
          formatPhone: formatTel
        },
        components: {myTitle,Checker, CheckerItem,myChecks,Cell,Flexbox,FlexboxItem,LoadMore}
    }
</script>

<style lang="scss" rel="stylesheet/scss" >
.send_mission{
  display: flex;
  flex-direction: column;
  overflow-y: hidden;
  height: 100vh;
  .fixed-btn{
    z-index:10;
  }
  .header{
    z-index: 10;
    position: relative;
    display: flex;
    padding: unit(20rem) unit(40rem);
    background-color: #fff;
    justify-content: space-between;
    color: $color999;
    .check_all{
      &.hide{
        visibility: hidden;
      }
    }
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
      &:after{
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

  .user_list{
    flex: 1;
    overflow-y: auto;
    position: relative;
    padding-top: unit(10rem);
    padding-left: unit(32rem);
    padding-bottom: 3.185rem;
    margin-top: unit(31rem);
    background-color: #fff;
    li{
      position: relative;
      display: flex;
      align-items: center;
      background-color: #fff;
      &:last-child{
        &:after{
          display: none;
        }
      }
      >input{
          opacity: 0;
          z-index:1;
          position: absolute;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
      }
      .weui-cell{
          width: 100%;
          @extend %weui_no_border;
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
        }
    }
  }
}
</style>
