import http from '../axios';

export function httpLogout() {
  const url = '/parent/logout';

  const data = Object.assign({}, {
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}

export function httpUpdatepw(oldpassword,newpassword,conpassword) {
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
