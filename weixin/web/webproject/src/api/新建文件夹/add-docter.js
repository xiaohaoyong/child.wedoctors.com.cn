import http from '../axios';

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

