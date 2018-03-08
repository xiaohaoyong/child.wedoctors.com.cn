import http from '../axios';

// export function httpArticle(article) {//获取文章列表
//   const url = '/parent/article-info';

//   const data = Object.assign({},{
//     "id": article
//   });

//   return http.get(url, {
//     params: data
//   }).then((response) => {
//     return Promise.resolve(response.data)
//   })
// }

export function httpGetcode(phone,type) {
  // phone 手机号
  // type  1注册2修改密码（不传默认1）
  const url = '/site/sendmessage'

  const data = Object.assign({},{
    phone: phone,
    type: type
  });

  /*return axios.get(url, {
    params: data
  }).then((res) => {
    return Promise.resolve(res.data)
  })
*/

  return http.post(url,data).then(function (response) {
    return Promise.resolve(response.data)
  });
}

export function httpAdddoctor(dotor_id) {
  const url = '/parent/add-doctor'

  const data = Object.assign({},{
    "doctor_id": dotor_id
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}

export function httpDoctorparent() {
  const url = '/parent/doctor-parent'

  const data = Object.assign({},{

  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}

export function httpFirstQRDoctorparent(dotor_id) {//扫码登录获取医生信息
  const url = '/site/doctor-parent'

  const data = Object.assign({},{
    "userid": dotor_id
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}


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


export function httpManageUser(type,age,page) {//签约状态/年龄段/分页
  const url = '/doctor/child-have';

  const data = Object.assign({},{
    "type": type,
    "age": age,
    "page": page
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}

// export function httpArticlelist(page,catid) {
//   const url = '/parent/article-list';

//   const data = Object.assign({},{
//     page: page,
//     catid: catid
//   });

//   return http.get(url, {
//     params: data
//   }).then((response) => {
//     return Promise.resolve(response.data)
//   })
// }

export function httpDoctorinfo(dotor_id) {
  const url = '/doctor/doctor-view'

  const data = Object.assign({},{
    "doctor_id": dotor_id
  });

  return http.get(url, {
    params: data
  }).then((response) => {
    return Promise.resolve(response.data)
  })
}


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


// export function httpChildinfo(id) {
//   const url = '/parent/child-info';

//   const data = Object.assign({},{
//     "id": id
//   });

//   return http.get(url, {
//     params: data
//   }).then((response) => {
//     return Promise.resolve(response.data)
//   })
// }

// export function httpChildinfo(id) {
//   const url = '/parent/child-info';

//   const data = Object.assign({},{
//     "id": id
//   });

//   return http.get(url, {
//     params: data
//   }).then((response) => {
//     return Promise.resolve(response.data)
//   })
// }
