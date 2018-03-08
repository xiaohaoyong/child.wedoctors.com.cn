import http from '../axios';

/*
宣教功能
 */

export function httpArticlelistuser(id,page) {
  const url = '/parent/article-user';

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

export function httpBackchildlist(num) {
  const url = '/doctor/backchild-list';

  const data = Object.assign({},{
    "num": num
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}

export function httpSendteach(artid,childid) {
  const url = '/doctor/send-teach'

  const data = Object.assign({},{
    artid: artid,
    childid: childid
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}


export function httpTypelistSend(id) {
  const url = '/article/type-list'

  const data = Object.assign({},{
    type: id
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}

export function httpArticlelistSend(child_type,list_id) {
  const url = '/article/send'

  const data = Object.assign({},{
    child_type: child_type,
    list: list_id
  });

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}

export function httpChildType(id) {
  const url = '/article/chile-type'

  const data = Object.assign({},{
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}

export function httpUserRead(nPage,type) {
  const url = '/article/list'

  const data = Object.assign({},{
    page: nPage,
    type: type
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}
