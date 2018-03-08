<template>
  <transition name="slide-left">
<div class="wait_sign_user">
  <my-title>我的健康档案</my-title>
  <div class="info">
    <cell >
      <img slot="icon" src="./avatar.png">
      <div slot="title">
        <span class="name">{{users.name}}<i>{{users.sex}}</i></span>
        <span class="tel">{{users.age}}</span>
      </div>
    </cell>
  </div>

  <ul class="parents vux-1px-t">
    <li><span>父亲姓名：</span>{{users.father}}</li>
    <li><span>父亲电话：</span>{{users.father_phone | formatPhone}}</li>
    <li><span>母亲姓名：</span>{{users.mother}}</li>
    <li><span>母亲电话：</span>{{users.mother_phone | formatPhone}}</li>
  </ul>
  <div class="control_common">
    <x-button type="primary" class="btn" @click.native="through" :disabled="throughDisable">{{throughBtn}}</x-button>
  </div>
</div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import { Cell, XButton, Group } from 'vux'
  import {formatTel} from  '../../common/js/vaildform'
  import {httpChildinfo} from 'my_api/get';
  import {httpAgreeapply} from 'my_api/post';
  const ERR_OK = 200;
  import { dateFormat } from 'vux'
    export default {
        data() {
            return {
              users: {
                name: '',
                sex: '',
                age: '',
                father: '',
                father_phone: '',
                mother: '',
                mother_phone: ''
              },
              throughBtn: '通过申请',
              throughDisable: false
            }
        },
        mounted() {
          let self = this;

          httpChildinfo(self.$route.query.id).then((response) => {
            if (response.code == ERR_OK){
              let oInfo = response.data.Child;
//              console.log(oInfo);
              self.users.name = oInfo.name
              self.users.sex = oInfo.gender
              self.users.age = oInfo.age;

              self.users.father = oInfo.parent.father;
              self.users.father_phone = oInfo.parent.father_phone;
              self.users.mother = oInfo.parent.mother;
              self.users.mother_phone = oInfo.parent.mother_phone;

            }else{
              self.$vux.toast.text('请求失败');
            }
          });
          /*self.$axios.get('/parent/child-info',{params: {
            "id": self.$route.query.id
          }}).then(function (response) {
            let data = response.data;
//            console.log(data);
            if (data.code == ERR_OK){
              let oInfo = data.data.Child;
//              console.log(oInfo);
              self.users.name = oInfo.name
              self.users.sex = oInfo.gender
              self.users.age = oInfo.age;

              self.users.father = oInfo.parent.father;
              self.users.father_phone = oInfo.parent.father_phone;
              self.users.mother = oInfo.parent.mother;
              self.users.mother_phone = oInfo.parent.mother_phone;

            }else{
              self.$vux.toast.text('请求失败');
            }
          }).catch(function (error) {
            console.warn(error);
            self.$vux.toast.text('网络错误');
          });*/

          /*self.$axios.get('/doctor/child-apply',{params: {
            "id": self.$route.query.id,
            "type": self.$route.query.type
          }}).then(function (response) {
            let data = response.data;
            self.aaa = data
            if (data.code == ERR_OK){
              self.babyInfo = data.data;
//              self.babyInfo = data.data.child;
            }else{
              self.$vux.toast.text('请求失败');
            }
          }).catch(function (error) {
            console.warn(error);
            self.$vux.toast.text('网络错误');
          });*/
        },
        methods: {
          through() {
            let self = this;

            httpAgreeapply(self.$route.query.id).then((response) => {
              if (response.code == ERR_OK){
                self.$vux.toast.text(response.msg);
                self.throughDisable = true;
                self.throughBtn = '已签约';
              }else{
                self.$vux.toast.text('请求失败');
              }
            });
            /*self.$axios.post('/doctor/agree-apply',{
              "id": self.$route.query.id
            }).then(function (response) {
              let data = response.data;
//              console.log(data);
              if (data.code == ERR_OK){
                self.$vux.toast.text(data.msg);
                self.throughDisable = true;
                self.throughBtn = '已签约';
              }else{
                self.$vux.toast.text('请求失败');
              }
            }).catch(function (error) {
              console.warn(error);
              self.$vux.toast.text('网络错误');
            });*/
//            this.$router.go(-1)
          }
        },
      filters: {
        formatPhone: formatTel
      },
        components: {myTitle,Cell,Group,
          XButton}
    }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
  .wait_sign_user{
    .info{
      display: flex;
      padding: unit(32rem);
      background-color: #fff;
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
            }
          }
        }
        .weui-cell__ft{
          font-size: unit(24rem);
        }
      }
    }
    .parents{
      padding: unit(30rem) 0;
      padding-left: 4%;
      background-color: #fff;
      color: #666666;
      font-size: unit(30rem);
      li{
        padding: unit(22rem) 0;
        letter-spacing: 0.05em;
        span{
          padding-right: unit(10rem);
        }
      }
      &:before{
        left: auto;
        right: 0;
        width: 96%;
      }
    }
  }
</style>
