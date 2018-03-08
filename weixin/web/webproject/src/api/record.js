import http from '../axios';

export function httpParentChildEx(childid) {
  const url = '/parent/child-ex'

  const data = Object.assign({},{
    id: childid
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}

export function httpParentChildExView(childid) {
  const url = '/parent/child-ex-view'

  const data = Object.assign({},{
    id: childid
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}
