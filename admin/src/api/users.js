import request from '@/utils/rest'

export function getUsers() {
  return request({
    url: 'users',
    method: 'get'
  })
}
