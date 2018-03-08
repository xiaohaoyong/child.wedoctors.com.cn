<template>
  <transition name="slide-left">
    <div class="edit_info">
      <my-title>{{title}}</my-title>

      <ul class="form_wrapper">
        <li class="item">
          <group title="基本资料">
            <x-input title="宝宝姓名" is-type="china-name" name="mobile" placeholder="请输入宝宝姓名" v-model="ChildInfo.name" :max="5"></x-input>
          </group>
        </li>
        <li class="item">
          <group>
            <span class="tips" v-show="showSextip">请选择</span>
            <popup-picker title="性别" :data="sexy" v-model="gender_text" @on-show="onShow" @on-hide="onHide" @on-change="onChange"></popup-picker>
          </group>
        </li>
        <li class="item">
          <group>
            <datetime v-model="ChildInfo.birthday" @on-change="change" title="生日" placeholder="请选择" :min-year='new Date().getFullYear()-8' :max-year='new Date().getFullYear()' year-row="{value}年" month-row="{value}月" day-row="{value}日"></datetime>
          </group>
        </li>
      </ul>

      <ul class="parents">
        <li class="item">
          <group title="详细信息">
            <x-input title="父亲姓名" is-type="china-name" placeholder="请输入父亲姓名" v-model="UserParent.fater" :max="5"></x-input>
          </group>
        </li><li class="item">
        <group>
          <x-input title="父亲手机号" is-type="china-mobile" placeholder="请输入父亲手机号" v-model="UserParent.father_phone" :max="11"></x-input>
        </group>
      </li><li class="item">
        <group>
          <x-input title="母亲姓名" is-type="china-name" placeholder="请输入母亲姓名" v-model="UserParent.mother" :max="5"></x-input>
        </group>
      </li><li class="item">
        <group>
          <x-input title="母亲手机号" is-type="china-mobile" placeholder="请输入母亲手机号" v-model="UserParent.mother_phone" :max="11"></x-input>
        </group>
      </li>
      </ul>

      <div class="control">
        <x-button type="primary" class="btn" @click.native="next">提交</x-button>
      </div>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import { Datetime,PopupPicker, XInput, Group, XButton, Cell } from 'vux'
  import myTitle from '../../base/title/title.vue'
  import {isObjEmpty,isTel} from  '../../common/js/vaildform'
  import {httpChildinfo} from 'my_api/get';
  import {httpUpdateparent} from 'my_api/post';
  const ERR_OK = 200;
  const ERR_NO = 11001;

  export default {
    name: 'new_baby',
    data() {
      return {
        title: '完善信息',
        ChildInfo: {
          name: '',
          gender: '',
          birthday: ''
        },
        UserParent: {
          fater: '',
          father_phone: '',
          mother: '',
          mother_phone: ''
        },
        showSextip: true,
        gender_text: [],
        sexy: [['男','女']]
      }
    },
    mounted() {
      this.title = this.$route.params.title ? this.$route.params.title : this.title;
    },
    methods: {
      change (value) {
//        console.log('change', value)
      },
      onChange (value) {
        this.showSextip = false; //如果发生change肯定选择了值
        if (value == '男'){
          this.ChildInfo.gender = '1';
        }else {
          this.ChildInfo.gender = '2';
        }
      },
      onShow () {
//        console.log('on show')
      },
      onHide () {
//        console.log('on ')
      },
      next() {
        let self = this;
        let _ChildInfo = self.ChildInfo;
        let _UserParent = self.UserParent;

        if (isTel.apply(self,[_UserParent.father_phone]) && isTel.apply(self,[_UserParent.mother_phone]) && isObjEmpty(_ChildInfo) && isObjEmpty(_UserParent)){

          httpUpdateparent(_ChildInfo,_UserParent).then((response) => {
            if (response.code == ERR_OK){
              self.$vux.toast.text(response.msg);
              setTimeout(()=>{
                if (self.$route.query.redirect === 'home'){//重定向到家长首页 否则到医生添加页面
                  self.$router.go(-1);
                }else {
                  self.$router.push({
                    path: `/add-docter`
                  })
                }
              },600)
            }else if(response.code == ERR_NO){
              self.$vux.toast.text(response.msg);
            }
          });
          /*self.$axios.post('/parent/update-parent',{
            "ChildInfo": _ChildInfo,
            "UserParent": _UserParent
          }).then(function (response) {
            let data = response.data;
            if (data.code == ERR_OK){
              self.$vux.toast.text(data.msg);
              setTimeout(()=>{
                if (self.$route.query.redirect === 'home'){//重定向到家长首页 否则到医生添加页面
                  self.$router.go(-1);
                }else {
                  self.$router.push({
                    path: `/add-docter`
                  })
                }
              },600)
            }else if(data.code == ERR_NO){
              self.$vux.toast.text(data.msg);
            }
          }).catch(function (error) {
            console.warn(error);
            self.$vux.toast.text('网络错误');
          });*/
        }else {
          self.$vux.toast.text('信息未填写完整 / 手机号格式错误');
        }
        /*this.$router.push({
          path: `/add-docter`
        })*/
      }
    },
    components: {
      XInput,
      XButton,
      Group,
      Cell,
      Datetime,
      PopupPicker,
      myTitle
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss">

  .edit_info{
    position: relative;
    min-height: 100vh;
    .weui-cells__title{
      margin-bottom: 0.7em;
    }
    .tips{
      position: absolute;
      right: 0;
      top: unit(18rem);
      right: unit(55rem);
      color: #c0c0c0;
      z-index: 0;
    }
    .weui-input{
      text-align: right;
    }
    .vux-cell-box{
      &:before{
        display: none;
      }
    }
    .parents{

    }
    .form_wrapper{
      padding-top: unit(40rem);
      .item{
        &:last-child{
          .weui-cells{
            &:after{
              display: none;
            }
          }
        }
        .weui-cells{
          margin-top:0;
          .vux-cell-value{
            color: #333;
          }
          &:before{
            display: none;
          }
          &:after{
            width: 96%;
            right: 0;
            left: auto;
          }
        }
      }
    }

    .parents{
      margin-top: unit(70rem);
      .item{
        &:last-child{
          .weui-cells{
            &:after{
              display: none;
            }
          }
        }
        .weui-cells{
          margin-top:0;
          &:before{
            display: none;
          }
          &:after{
            width: 96%;
            right: 0;
            left: auto;
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
