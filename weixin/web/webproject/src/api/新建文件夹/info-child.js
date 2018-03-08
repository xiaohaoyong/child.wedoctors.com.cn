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