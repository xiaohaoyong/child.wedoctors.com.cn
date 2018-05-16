import http from '../axios';

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
