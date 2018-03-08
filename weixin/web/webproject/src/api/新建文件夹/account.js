import {commonParams} from './config'
import http from '../axios';

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
