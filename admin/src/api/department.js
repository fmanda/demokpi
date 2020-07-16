import request from '@/utils/rest'

export function getDepartment(deptid) {
  return request({
    url: 'department/' + deptid.toString(),
    method: 'get'
  })
}

export function getKPIDept(deptid, year) {
  return request({
    url: 'kpidept/' + deptid.toString() + '/' + year.toString(),
    method: 'get'
  })
}

export function postDepartment(data) {
  return request({
    url: 'department',
    method: 'post',
    data
  })
}
