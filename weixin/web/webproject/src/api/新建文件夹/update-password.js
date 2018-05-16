import http from '../axios';

export function httpUpdatepw(phone,password,verify) {
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
