import request from '@/utils/rest'

export function getDepartment(deptid) {
  return request({
    url: 'department/' + deptid.toString(),
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
