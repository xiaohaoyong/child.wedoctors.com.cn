import http from '../axios';

export function httpHomedocter() {
  const url = '/doctor/doctor-index';

  const data = Object.assign({},{
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}

export function httpHomeparent() {
  const url = '/parent/child-list';

  const data = Object.assign({},{
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}
