<template>
  <transition name="slide-left">
    <section class="docter_home">
      <my-title>儿童中医健康管理</my-title>
      <div class="info">
        <cell :link="{path:'/docter-self'}" value="我的主页">
          <img slot="icon" src="./avatar.png">
          <!--<img slot="icon" :src="docterInfo.avatar">-->
          <div slot="title">
            <span class="name">{{docterInfo.name}}</span>
          </div>
        </cell>
        <ul class="overview vux-1px-t">
          <li>
            <a href="javascript:;" class="link vux-1px-r">
              <!--<strong><countup :start-val="0" :end-val="docterInfo.child_num" :duration="2"></countup></strong>-->
              <strong>{{docterInfo.child_num}}</strong>
              <span>签约儿童数</span>
            </a>
          </li>
          <li>
            <a href="javascript:;" class="link">
              <!--<strong><countup :start-val="0" :end-val="5345" :duration="2"></countup></strong>-->
              <strong>{{docterInfo.teach_num}}</strong>
              <span>宣教次数</span>
            </a>
          </li>
        </ul>
      </div>

      <section class="menu_list">
        <group class="item">
          <cell title="管辖儿童" :link="{path: '/docters-user'}" value="儿童健康档案" >
            <img slot="icon" width="20" src="./icon-clients.png">
          </cell>
        </group>
        <group class="item">
          <cell title="咨询解答" :link="{path: '/doctor-select-talk'}" value="在线答疑" >
            <img slot="icon" width="20" src="./icon-talk.png">
            <badge :text="docterInfo.message" v-if="Number(docterInfo.message)"></badge>
          </cell>
        </group>
        <group class="item">
          <cell title="宣教任务" :link="{path:'/docter-missiontask'}" value="本月宣教工作">
            <img slot="icon" width="20" src="./icon-missons.png">
          </cell>
        </group>
        <group class="item">
          <cell title="预防宣传" :link="{path:'/parents-classroom',query: {title: 'docter'}}" value="儿童健康宣教知识库">
            <img slot="icon" width="20" src="./icon-articles.png">
          </cell>
        </group>
        <group class="item">
          <cell title="设置" :link="{path:'/docter-setting'}" value="获取二维码,修改密码">
            <img slot="icon" width="20" src="./icon-setting.png">
          </cell>
        </group>
      </section>
    </section>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import { Cell, XButton, Group,Countup,Badge } from 'vux'
  import {httpHomedocter} from 'my_api/get';
const ERR_OK = 200;
  export default {
    data() {
      return {
        docterInfo: ''
      }
    },
    created() {
      let self = this;
      httpHomedocter().then((response) => {
        if (response.code == ERR_OK){
          window.sessionStorage.setItem('QRcode_id',response.data.doctorid);//存储医生的id
          self.docterInfo = response.data;
        }else{
          self.$vux.toast.text('请求失败');
        }
      });
      /*self.$axios.get('/doctor/doctor-index').then(function (response) {
        let data = response.data;
        if (data.code == ERR_OK){
          window.sessionStorage.setItem('QRcode_id',data.data.doctorid);//存储医生的id
          self.docterInfo = data.data;
        }else{
          self.$vux.toast.text('请求失败');
        }
      }).catch(function (error) {
        console.warn(error);
        self.$vux.toast.text('网络错误');
      });*/
    },
    methods: {

    },
    components: {
      myTitle,Cell,Group,Countup,
      XButton,Badge
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss">
.docter_home{
  .info{
    padding: unit(32rem);
    padding-right: 0;
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
            font-size: unit(36rem);
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
          right: 2px;
        }
      }
    }
    .overview{
      display: flex;
      padding: unit(30rem) unit(52rem) 0 unit(20rem);
      text-align: center;
      li{
        flex:1;
        .link{
          display: block;
          &:active{
            opacity: 0.7;
          }
          >strong,>span{
            display: block;
          }
          >strong{
            font-size: unit(48rem);
            color: #333333;
          }
          >span{
            padding-top: unit(24rem);
            font-size: unit(24rem);
            color: #bbb;
          }
        }
      }
    }
  }
  .menu_list{
    margin-top: unit(39rem);
    .item{
      @extend %weui_top_border;
      .weui-cell{
        padding-top: unit(34rem);
        padding-bottom: unit(30rem);
        img{
          display: block;
          width: unit(45rem);
          margin-left: unit(18rem);
          margin-right: unit(32rem);
        }
        .vux-cell-bd{
          font-size: unit(32rem);
          color: #333;
        }
        .weui-cell__ft{
          color: $colorbbb;
          font-size: unit(24rem);
        }
      }
    }
  }
}
</style>
