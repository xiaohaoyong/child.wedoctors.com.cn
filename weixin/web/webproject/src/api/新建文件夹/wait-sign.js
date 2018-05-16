import http from '../axios';

export function httpChildinfo(id) {
  const url = '/parent/child-info';

  const data = Object.assign({},{
    "id": id
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
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
