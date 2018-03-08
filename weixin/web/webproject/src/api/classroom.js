import http from '../axios';

/*
获取文章/课堂的信息
 */

export function httpTabs() {
  const url = '/parent/article-cate';

  const data = Object.assign({},{
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}

export function httpArticlelist(page,catid) {
  const url = '/parent/article-list';

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
