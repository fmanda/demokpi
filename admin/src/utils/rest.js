import axios from 'axios'
import { Message } from 'element-ui'

const rest = axios.create({
  baseURL: process.env.VUE_APP_BASE_URL,
  timeout: 5000 // request timeout
})

// request interceptor
rest.interceptors.request.use(
  config => {
    // do something before request is sent
    // if (store.getters.token) {
    //   config.headers['X-Token'] = getToken()
    // }
    // console.log(process.env)
    return config
  },
  error => {
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
rest.interceptors.response.use(
  response => {
    return response
  },
  error => {
    console.log('err' + error) // for debug
    Message({
      message: error.message,
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)

export default rest
