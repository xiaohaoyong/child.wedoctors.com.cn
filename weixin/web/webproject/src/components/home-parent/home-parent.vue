<template>
  <transition name="slide-left">
  <div class="parent_home">
    <my-title>儿童中医健康管理</my-title>
    <section class="swipe_wrapper">
      <swiper :options="swiperOption" :not-next-tick="notNextTick" ref="mySwiper" class="banner_parent">
        <!--<swiper-slide v-for="(item,$index) in 2">-->
          <!--<section class="card">-->
            <!--<div class="info">-->
              <!--<img src="./avatar-child.png" alt="">-->
              <!--<p>-->
                <!--<b>小白</b>-->
                <!--<span>2岁5个月3天</span>-->
              <!--</p>-->
            <!--</div>-->
            <!--<div class="record">-->
              <!--<group>-->
                <!--<cell title="健康档案" is-link :link="{path:'/record'}">-->
                  <!--<img slot="icon" width="20" src="./cardicon.png">-->
                <!--</cell>-->
              <!--</group>-->
            <!--</div>-->
          <!--</section>-->
        <!--</swiper-slide>-->
        <swiper-slide v-for="(item,$index) in child_list" :key="$index">
          <section class="card">
            <cell class="link_mask" :link="{path:'/record',query: {'id': item.id}}"></cell>
            <div class="info">
              <img src="./avatar-child.png" alt="">
              <p>
                <b>{{item.name}}</b>
                <span>{{item.age}}</span>
              </p>
            </div>
            <div class="record">
              <group>
                <cell title="健康档案" is-link>
                  <img slot="icon" width="20" src="./cardicon.png">
                </cell>
              </group>
            </div>
          </section>
        </swiper-slide>
        <swiper-slide v-if="add_child">
          <section class="card card-add" @click="add_baby">
            <span><i class="icon"></i>添加宝宝</span>
          </section>
        </swiper-slide>
        <div class="swiper-pagination dote"  slot="pagination"></div>
      </swiper>
    </section>
    <section class="menu_list">
      <group class="item">
        <!--<cell title="儿保顾问" :link="{name:'docter-info', params: {'title':'儿保顾问'}}" value="我签约的儿保医生" >
          <img slot="icon" width="20" src="./icon-consultant.png">
        </cell>-->
        <cell title="儿保顾问" :link="{path:'/child-advisers'}" value="我签约的儿保医生" >
          <img slot="icon" width="20" src="./icon-consultant.png">
          <badge v-if="Number(message)"></badge>
        </cell>
      </group>
      <group class="item">
        <cell title="家长课堂" :link="{path:'/parents-classroom'}" value="运动饮食、疾病预防">
          <img slot="icon" width="20" src="./icon-prevent.png">
        </cell>
      </group>
      <group class="item">
        <cell title="设置" :link="{path:'/setting'}" value="退出登录">
          <img slot="icon" width="20" src="./icon-setting.png">
        </cell>
      </group>
    </section>
  </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import { Cell, CellBox, CellFormPreview, Group, Badge } from 'vux'
  import {httpHomeparent} from 'my_api/get';
  const ERR_OK = 200;
  const MAX_child = 4;

    export default {
        data() {
          return {
            child_list: [],
            message: '',
            add_child: true,
            // notNextTick是一个组件自有属性，如果notNextTick设置为true，组件则不会通过NextTick来实例化swiper，也就意味着你可以在第一时间获取到swiper对象，假如你需要刚加载遍使用获取swiper对象来做什么事，那么这个属性一定要是true
            notNextTick: true,
            swiperOption: {
              // swiper optionss 所有的配置同swiper官方api配置
//              spaceBetween: 10,
              pagination : '.swiper-pagination',
              paginationClickable :true,
              slidesPerView: 'auto',
              paginationClickable: true,
              // if you need use plugins in the swiper, you can config in here like this
              // 如果自行设计了插件，那么插件的一些配置相关参数，也应该出现在这个对象中，如下debugger
              debugger: true,
              // swiper callbacks
              // swiper的各种回调函数也可以出现在这个对象中，和swiper官方一样
              onTransitionStart(swiper){
//                console.log(swiper)
              }
            }
          }
        },
      mounted() {
        window.sessionStorage.removeItem('doctor_id');//可能不关闭浏览器就访问医生首页 需要清除缓存的医生id
          this.fetchList();
      },
        computed: {
          swiper() {
            return this.$refs.mySwiper.swiper
          }
        },
        methods: {
          fetchList() {
            let self = this;
            httpHomeparent().then((response) => {
              if (response.code == ERR_OK){
                let data = response.data;
                if (!response.data) return;//没有宝宝信息不处理渲染
                if (data.length > MAX_child){
                  self.add_child=false
                }
//            console.log(response);
                self.child_list = data;
                self.message = response.message;//未读消息
              }else{
                self.$vux.toast.text('请刷新重试');
              }
            });
            /*self.$axios.get('/parent/child-list',{}).then(function (response) {

              if (response.data.code == ERR_OK){
                let data = response.data.data;
                if (!response.data.data) return;//没有宝宝信息不处理渲染
                if (data.length > MAX_child){
                  self.add_child=false
                }
//            console.log(response.data);
                self.child_list = data;
              }else{
                self.$vux.toast.text('请刷新重试');
              }
            }).catch(function (error) {
              self.$vux.toast.text('网络错误');
            });*/
          },
          add_baby() {
            this.$router.push({name: 'new_baby',query: {'redirect': 'home'},params:{title:'添加宝宝'}});
          }
        },
        components: {myTitle,Group,
          Cell,
          CellFormPreview,
          CellBox,
          Badge}
    }
</script>

<style lang="scss" rel="stylesheet/scss">
.parent_home{
  min-height: 100vh;
  .swipe_wrapper{//小点样式
    .dote{
      top: unit(20rem);
      bottom: auto;
      .swiper-pagination-bullet{
        opacity: 1;
        width: unit(20rem);
        height: unit(20rem);
        background-color: transparent;
        border: unit(2rem) solid #f47961;
        transition: width 0.3s;
        &.swiper-pagination-bullet-active{
          width: unit(34rem);
          border-radius: unit(20rem);
          background-color: #f47961;
        }
      }
    }
  }
  .banner_parent{//卡片主体
    width: 100%;
    .swiper-wrapper{
      .swiper-slide{
        width: 90%;
        height: 100%;
        padding: unit(80rem) 0 0 unit(32rem);
        &:last-child{
          padding-right: unit(32rem);
        }
        display: flex;
        justify-content: center;
        align-items: center;
        .card{
          position: relative;
          width: 100%;
          height: 11.2375rem;
          overflow: hidden;
          box-shadow: 0 0 unit(30rem) rgba(0, 0, 0, 0.05);
          background-color: #f47961;
          background-image: linear-gradient(-223deg, #ea8942 0%, #ea8942 1%, #e9583c 100%);
          border-radius: unit(18rem);
          .link_mask{
            opacity: 0;
            position: absolute;
            z-index: 10;
            left:0;
            top:0;
            width: 100%;
            height: 100%;
          }
          &.card-add{
            background: #fff;
            display: flex;
            align-items: center;
            text-align: center;
            span{
              flex: 1;
              font-size: unit(34rem);
              color: $color999;
              .icon{
                display: inline-block;
                margin-right: unit(30rem);
                vertical-align: middle;
                width: unit(67rem);
                height: unit(67rem);
                background: no-repeat center / contain;
                @include bg-image('./add-baby');
              }
            }
          }
          .info{
            display: flex;
            align-items: center;
            padding: unit(46rem) unit(40rem) unit(25rem);
            img{
              width: unit(146rem);
              height: unit(146rem);
            }
            P{
              padding-left: unit(34rem);
              color: #fff;
              b,span{
                display: block;
                padding: unit(14rem) 0;
              }
              b{
                font-size: unit(36rem);
              }
              span{
                font-size: unit(28rem);
              }
            }
          }
          .record{
            width: 100%;
            padding-top: unit(10rem);
            height: 4.66875rem;
            background: url("./cardbg.png") no-repeat center;
            background-size: contain;
            .weui-cells{
              background: none;
              &:before,&:after{
                display: none;
              }
              .weui-cell{
                &:active{
                  opacity: 0.7;
                  background: none;
                }
                img{
                  display:block;
                  width: unit(45rem);
                  margin-right: unit(25rem);
                }
              }
            }
            /*flex-flow: column;*/
          }
        }
      }
    }
  }
  .menu_list{
    margin-top: unit(32rem);
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
