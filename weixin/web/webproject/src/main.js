// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './vuex'
import './axios'
import VueAwesomeSwiper from 'vue-awesome-swiper'

Vue.use(require('vue-wechat-title')) //改变标题

require('swiper/dist/css/swiper.css')
Vue.use(VueAwesomeSwiper)
window.WebIm=require('easemob-websdk')
import  { ToastPlugin } from 'vux'
Vue.use(ToastPlugin)


import 'my_common/css/reset.css';//引入css reset
import 'my_common/css/common.css';//引入css reset
import 'my_common/sass/mixin.scss';//引入sass mixin

Vue.directive('title', {
  inserted: function (el, binding) {
    document.title = el.innerText
    el.remove()
  }
})


/*store.registerModule('loading', { // 设置延时
  state: {
    loading: false,
    isLoading: false
  },
  mutations: {
    updateLoadingStatus (state, payload) {
      state.isLoading = payload.isLoading
    }
  }
})
router.beforeEach(function (to, from, next) {
  store.commit('updateLoadingStatus', {isLoading: true})
  next()
})

router.afterEach(function (to) {
  store.commit('updateLoadingStatus', {isLoading: false})
})*/


const FastClick = require('fastclick')//点击延时
FastClick.attach(document.body)

Vue.config.productionTip = false;//禁止vue启动提示

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
})

