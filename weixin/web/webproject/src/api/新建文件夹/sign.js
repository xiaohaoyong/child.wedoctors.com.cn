import http from '../axios';

export function httpSign(phone,verify) {

  const url = '/site/reg';

  const data = Object.assign({}, {
    phone: phone,
    // password: password,
    verify: verify
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}
