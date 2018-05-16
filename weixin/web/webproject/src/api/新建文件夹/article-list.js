import http from '../axios';

export function httpArticlelist(page,catid) {
  const url = '/parent/article-list'

  const data = Object.assign({},{
    page: page,
    catid: catid
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}

