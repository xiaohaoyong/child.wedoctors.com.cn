import http from '../axios';

export function httpArticle(article) {
  const url = '/parent/article-info';

  const data = Object.assign({},{
    "id": article
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}
