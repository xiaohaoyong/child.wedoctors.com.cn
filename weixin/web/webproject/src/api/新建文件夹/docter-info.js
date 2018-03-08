import http from '../axios';

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
