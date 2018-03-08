import http from '../axios';
import {commonParams} from './config';


export function httpGetcode(phone,type) {
  // phone 手机号
  // type  1注册2修改密码（不传默认1）
  const url = '/site/sendmessage'

  const data = Object.assign({},{
    phone: phone,
    type: type
  });

  /*return axios.get(url, {
    params: data
  }).then((res) => {
    return Promise.resolve(res.data)
  })
*/

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}


export function httpAccount(phone,password,type) {
  const url = '/site/login'

  const data = Object.assign({},/* commonParams,*/ {
    phone: phone,
    password: password,
    type: type //医生type0  用户1
  });

  /*return axios.get(url, {
    params: data
  }).then((res) => {
    return Promise.resolve(res.data)
  })
*/

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}


export function httpRecordadd(id,content) {
  const url = '/doctor/add-health'

  const data = Object.assign({},{
    id: id,
    content: content
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}


export function httpUpdateparent(ChildInfo,UserParent,id) {
  const url = '/parent/update-parent'

  const data = Object.assign({},{
    "ChildInfo": ChildInfo,
    "UserParent": UserParent,
    "id": id
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}

export function httpFirstUpdateparent(ChildInfo,id) {
  const url = '/parent/update-parent-xu'

  const data = Object.assign({},{
    "ChildInfo": ChildInfo,
    "id": id
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}


export function httpLogout() {
  const url = '/parent/logout';

  const data = Object.assign({}, {
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}

export function httpUpdatepw_old(oldpassword,newpassword,conpassword) {//旧修改密码
  const url = '/parent/update-password';

  const data = Object.assign({}, {
    oldpassword: oldpassword,
    newpassword: newpassword,
    conpassword: conpassword
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}


export function httpSign(phone,verify,doctor_id) {

  const url = '/site/reg';

  const data = Object.assign({}, {
    phone: phone,
    // password: password,
    verify: verify,
    doctor_id: doctor_id
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}


export function httpUpdatepw(phone,password,verify) {//新修改密码
  const url = '/site/update-password'

  const data = Object.assign({},{
    phone: phone,
    password: password,
    verify: verify
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}


export function httpAgreeapply(id) {
  const url = '/doctor/agree-apply'

  const data = Object.assign({},{
    id: id
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}