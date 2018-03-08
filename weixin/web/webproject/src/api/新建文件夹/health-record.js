import http from '../axios';

export function httpChildinfo(id) {
  const url = '/parent/child-info'

  const data = Object.assign({},{
    "id": id
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}

export function httpHealthrecord(id,page) {
  const url = '/parent/health-record';

  const data = Object.assign({},{
    "id": id,
    "page": page
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
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

export function httpDocterChildinfo(id) {
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
