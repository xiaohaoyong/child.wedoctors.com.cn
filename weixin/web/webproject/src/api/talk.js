import Vue from 'vue'
import  { LoadingPlugin } from 'vux'
import {commonParams} from './config'
import http from '../axios';

export function httpTalkParent(touserid,content,type) {//医生的id  内容   ->家长聊天界面
  const url = '/chat/add'

  const data = Object.assign({},/* commonParams,*/ {
    touserid: touserid,
    content: content,
    type: type
  });

  http.interceptors.request.use(function (config) {
    Vue.$vux.loading.hide();
    setTimeout(function () {
        Vue.$vux.loading.hide();
    }, 0)
    return config;
  }, function (error) {
    return Promise.reject(error);
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}


export function httpTalkKeep(userid,touserid) {//当前用户/聊天对象用户
  const url = '/chat/user-chat'

  const data = Object.assign({},/* commonParams,*/ {
    userid: userid,
    touserid: touserid
  });

  http.interceptors.request.use(function (config) {
    Vue.$vux.loading.hide();
    setTimeout(function () {
        Vue.$vux.loading.hide();
    }, 0)
    return config;
  }, function (error) {
    // Do something with request error
    return Promise.reject(error);
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  });
}


export function httpDoctorTalkList() {//当前用户/聊天对象用户
  const url = '/doctor/parent'

  const data = Object.assign({},{

  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  });
}


export function httpDoctorTalkPageFamilyCount(id) {//医生聊天页面 家庭人员总数
  const url = '/parent/childs'

  const data = Object.assign({},{
    'id': id,
    'type': 'count'
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  });
}

export function httpDoctorFamilyList(id) {//医生--  家庭儿童列表
  const url = '/parent/childs'

  const data = Object.assign({},{
    'id': id
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  });
}