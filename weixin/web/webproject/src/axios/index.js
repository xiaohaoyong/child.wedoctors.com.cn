import Vue from 'vue'
import axios from 'axios'
import  { LoadingPlugin } from 'vux'
import Qs from 'qs'
Vue.use(LoadingPlugin)
 // window.Qs = Qs
// console.log(window.Qs);
let self = this;



var axios_instance = axios.create({
  transformRequest: [function (data) {
    data = Qs.stringify(data);
    return data;
  }],
  // headers:{'Content-Type':'application/x-www-form-urlencoded'},
  // timeout: 5000
})

let isDev = process.env.NODE_ENV === 'development';
if (isDev){//如果是开发环境 设置一个模拟的请求头
  axios_instance.defaults.baseURL = 'https://easy-mock.com/mock/59c9e770e0dc663341bae164'; //设置baseurl mock地址
}

axios_instance.interceptors.request.use(config => {
  //在发送请求之前做某事，比如说 设置loading动画显示
  Vue.$vux.loading.show({
    text: '加载中'
  });
  return config
}, error => {
  Vue.$vux.loading.hide();
  // console.log('请求失败');
  Vue.$vux.toast.text('请求错误');
  //请求错误时做些事
  return Promise.reject(error)
})

//添加响应拦截器
axios_instance.interceptors.response.use(response => {
  //对响应数据做些事，比如说把loading动画关掉
  // console.log('请求完毕');

  // console.log(response.data.code);
  if (response.data.code === '50000'){//如果axios中没有返回过50000  可以重定向到登录页

    // window.location = response.data.msg;
  }
  Vue.$vux.loading.hide();
  return response
}, error => {
  Vue.$vux.loading.hide();
  Vue.$vux.toast.text('请求错误');
  //请求错误时做些事
  return Promise.reject(error)
})

Vue.prototype.$axios = axios_instance;

export default axios_instance;
