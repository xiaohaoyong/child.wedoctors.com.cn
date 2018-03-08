<template>
  <transition name="slide-left">
    <div class="edit_info">
      <my-title>{{title}}</my-title>

      <ul class="form_wrapper">
        <li class="item">
          <group title="基本资料">
            <x-input :disabled="disabled" title="宝宝姓名" is-type="china-name" name="mobile" placeholder="请输入宝宝姓名" v-model="ChildInfo.name" :max="5"></x-input>
          </group>
        </li>
        <li class="item">
          <div class="disabled" v-if="disabled">111</div>
          <group>
            <span class="tips" v-show="showSextip">请选择</span>
            <popup-picker title="性别" :data="sexy" v-model="gender_text" @on-show="onShow" @on-hide="onHide" @on-change="onChange"></popup-picker>
          </group>
        </li>
        <li class="item">
          <div class="disabled" v-if="disabled">111</div>
          <group>
            <datetime disabled v-model="ChildInfo.birthday" @on-change="change" title="生日" placeholder="请选择" :min-year='new Date().getFullYear()-8' :max-year='new Date().getFullYear()' year-row="{value}年" month-row="{value}月" day-row="{value}日"></datetime>
          </group>
        </li>
      </ul>

      <ul class="parents">
        <li class="item">
          <group title="详细信息">
            <x-input :disabled="disabled" title="父亲姓名" is-type="china-name" placeholder="请输入父亲姓名" v-model="UserParent.fater" :max="5"></x-input>
          </group>
        </li><li class="item">
        <group>
          <x-input v-if="this.$route.query.type == 'parent'" :disabled="disabled" title="父亲手机号" is-type="china-mobile" placeholder="请输入父亲手机号" v-model="UserParent.father_phone" :max="11"></x-input>
          <x-input v-else :disabled="disabled" title="父亲手机号" placeholder="请输入父亲手机号" v-model="UserParent.father_phone" :max="11"></x-input>
        </group>
      </li><li class="item">
        <group>
          <x-input :disabled="disabled" title="母亲姓名" is-type="china-name" placeholder="请输入母亲姓名" v-model="UserParent.mother" :max="5"></x-input>
        </group>
      </li><li class="item">
        <group>
          <x-input v-if="this.$route.query.type == 'parent'" :disabled="disabled" title="母亲手机号" is-type="china-mobile" placeholder="请输入母亲手机号" v-model="UserParent.mother_phone" :max="11"></x-input>
          <x-input v-else :disabled="disabled" title="母亲手机号" placeholder="请输入母亲手机号" v-model="UserParent.mother_phone" :max="11"></x-input>
        </group>
      </li>
      </ul>

      <div class="control" v-show="!disabled">
        <x-button type="primary" class="btn" @click.native="next">修改信息</x-button>
      </div>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import { Datetime,PopupPicker, XInput, Group, XButton, Cell } from 'vux'
  import myTitle from '../../base/title/title.vue'
  import {isObjEmpty,isTel} from  '../../common/js/vaildform'
  import * as Valide from  '../../common/js/vaildform'
  import {httpChildinfo} from 'my_api/get';
  import {httpUpdateparent} from 'my_api/post';
  const ERR_OK = 200;
  const ERR_NO = 11001;
  import { dateFormat } from 'vux'

  export default {
    name: 'edit_baby',
    data() {
      return {
        title: '编辑资料',
        disabled: true,
        ChildInfo: {
          name: '',
          gender: [],
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
    created() {
      (this.$route.query.type == 'parent') && (this.disabled = false);
      this.fetch();
    },
    mounted() {
//      console.log(this.$route.query.type);
//      this.title = this.$route.params.title ? this.$route.params.title : this.title;
    },
    methods: {
      fetch() {
        let self = this;
        httpChildinfo(self.$route.query.id).then((response) => {
          if (response.code == ERR_OK){
            let Infos = response.data;

            self.ChildInfo.name = Infos.Child.name;
            if (Infos.Child.gender){
              self.gender_text = [Infos.Child.gender];
              self.ChildInfo.gender = Infos.Child.gender == '男' ? '1' : '2';
              self.showSextip = false;
            }
            self.ChildInfo.birthday = dateFormat(Infos.Child.birthday*1000, 'YYYY-MM-DD');

            if (this.$route.query.type == 'parent'){
              self.UserParent.fater = Infos.Child.parent.father || '';
              self.UserParent.father_phone = Infos.Child.parent.father_phone || '';
              self.UserParent.mother = Infos.Child.parent.mother || '';
              self.UserParent.mother_phone = Infos.Child.parent.mother_phone || '';
            }else {
              self.UserParent.fater = Infos.Child.parent.father || '用户未填写';
              self.UserParent.father_phone = Infos.Child.parent.father_phone || '用户未填写';
              self.UserParent.mother = Infos.Child.parent.mother || '用户未填写';
              self.UserParent.mother_phone = Infos.Child.parent.mother_phone || '用户未填写';
            }


          }else{
            self.$vux.toast.text('请求失败');
          }
        });
        /*self.$axios.get('/parent/child-info',{params: {//基本信息
          id: self.$route.query.id
        }}).then(function (response) {
          let data = response.data;
          if (data.code == ERR_OK){
            let Infos = data.data;

            self.ChildInfo.name = Infos.Child.name;
            if (Infos.Child.gender){
              self.gender_text = [Infos.Child.gender];
              self.ChildInfo.gender = Infos.Child.gender == '男' ? '1' : '2';
              self.showSextip = false;
            }
            self.ChildInfo.birthday = dateFormat(Infos.Child.birthday*1000, 'YYYY-MM-DD');

            self.UserParent.fater = Infos.Child.parent.father;
            self.UserParent.father_phone = Infos.Child.parent.father_phone;
            self.UserParent.mother = Infos.Child.parent.mother;
            self.UserParent.mother_phone = Infos.Child.parent.mother_phone;

          }else{
            self.$vux.toast.text('请求失败');
          }
        }).catch(function (error) {
          self.$vux.toast.text('网络错误');
        });*/
      },
      change (value) {
        console.log('change', value)
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

        try //如果走这个判断不成功强制提交  实在没办法了  阿西吧
        {
          if (_UserParent.fater.length && _UserParent.mother.length && isTel.apply(self,[_UserParent.father_phone]) && isTel.apply(self,[_UserParent.mother_phone]) && isObjEmpty(_ChildInfo)){
            httpUpdateparent(_ChildInfo,_UserParent,self.$route.query.id).then((response) => {
              if (response.code == ERR_OK){
                self.$vux.toast.text(response.msg);
                setTimeout(()=>{
                  self.$router.go(-1);
                },600)
              }else if(response.code == ERR_NO){
                self.$vux.toast.text(response.msg);
              }else{
                self.$vux.toast.text('修改失败');
              }
            });
            /*self.$axios.post('/parent/update-parent',{
              "ChildInfo": _ChildInfo,
              "UserParent": _UserParent,
              "id": self.$route.query.id
            }).then(function (response) {
              let data = response.data;
              if (data.code == ERR_OK){
                self.$vux.toast.text(data.msg);
                setTimeout(()=>{
                  self.$router.go(-1);
                },600)
              }else if(data.code == ERR_NO){
                self.$vux.toast.text(data.msg);
              }else{
                self.$vux.toast.text('修改失败');
              }
            }).catch(function (error) {
              console.warn(error);
              self.$vux.toast.text('网络错误');
            });*/
          }else {
            self.$vux.toast.text('信息未填写完整 / 手机号格式错误');
          }
        }
        catch(err)
        {
          httpUpdateparent(_ChildInfo,_UserParent,self.$route.query.id).then((response) => {
            if (response.code == ERR_OK){
              self.$vux.toast.text(response.msg);
              setTimeout(()=>{
                self.$router.go(-1);
              },600)
            }else if(response.code == ERR_NO){
              self.$vux.toast.text(response.msg);
            }else{
              self.$vux.toast.text('修改失败');
            }
          });
        }

        /*console.log(this.ChildInfo);
        console.log(this.UserParent);*/
//        this.$router.go(-1)
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
        position: relative;
        >.disabled{//医生禁止填写信息
          position: absolute;
          opacity: 0;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          z-index: 1;
        }
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
      position: relative;
      z-index:10;
      padding: unit(90rem) unit(50rem) 0;
      text-align: center;
      .btn{
        z-index:1;
        border-radius: unit(90rem);
        height: unit(90rem);
      }
    }
  }

</style>
