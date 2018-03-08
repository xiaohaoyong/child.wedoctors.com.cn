<template>
  <transition name="slide-left">
    <div class="docter_users_manager">
      <my-title>管辖儿童</my-title>
      <div class="wrapper">
        <tab :line-width=2 :active-color='activeColor' :custom-bar-width="getBarWidth">
          <tab-item v-for="item in navdata"
                    :selected="active == item.type"
                    :key="item.type"
                    @on-item-click="active = item.type">
            {{item.title}}
          </tab-item>
          <!--<tab-item :selected="isType == 1" @on-item-click="onItemClick">已签约</tab-item>
          <tab-item :selected="isType == 0" @on-item-click="onItemClick">待签约</tab-item>
          <tab-item :selected="isType == -1" @on-item-click="onItemClick">未签约</tab-item>-->
        </tab>
        <section class="tab_con_wrapper">

          <div class="tab_con"
               v-for="item in navdata"
               v-show="item.type == active"
               :key="showItems">
            <!--<keep-alive>-->
            <my-scroll :tab-type="item.type"></my-scroll>

            <!--</keep-alive>-->
            <!--{{item.title}}-->

          </div>

        </section>
      </div>
    </div>
  </transition>
</template>

<script type="text/ecmascript-6">
  import myTitle from '../../base/title/title.vue'
  import { Checker, CheckerItem,Tab, TabItem, Sticky, Divider, XButton, Swiper, SwiperItem,Group,Cell,LoadMore } from 'vux'
  import myScroll from '../../base/docter-manage-list/docter-manage-list.vue'
  import { httpManageUser } from 'my_api/get';
  import { httpChildType } from 'my_api/missions';

  import { querystring } from 'vux'
  const ERR_OK = 200;
  export default {
    data() {
      return {
        navdata: [
          {
            'type': '1',
            'title': '已签约'
          },
          {
            'type': '0',
            'title': '未签约'
          }
        ],
        active: 1,
        getBarWidth: function (index) {
          return  50 + 'px'
          return (index + 1) * 22 + 'px'
        },
        btn_check_all: true,
        screen_data: '',
      }
    },
    created() {
      this.activeColor = '#f57961';
//      this.fetchChildType()
////          console.log(this.$route.query);
//      if (this.$route.query.p == 2){
//        let self = this;
//        this.isType = 0;
//        self.onItemClick(1);
//        return
////            this.showItems = 1;
//      }
////          console.log(querystring.parse(('p=2'));
//      this.fetch(this.active)
    },
    computed: {
      showItems() {//改变显示的类型 控制

        return this.index;
      }
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
      }
    },
    components: {myTitle,myScroll,Tab,Checker, CheckerItem,
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
      .tab_con_wrapper{
        flex: 1;

        .tab_con{
          height: 100%;
        }

      }
    }

  }
</style>
