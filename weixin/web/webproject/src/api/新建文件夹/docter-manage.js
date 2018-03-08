import http from '../axios';

export function httpManageUser(type,age) {//签约状态/年龄段
  const url = '/doctor/child-have';

  const data = Object.assign({},{
    "type": type,
    "age": age
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}
