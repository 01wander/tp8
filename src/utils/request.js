import axios from 'axios'
import { message } from 'ant-design-vue'

const request = axios.create({
    baseURL: 'http://localhost:8008', // 这里配置你的后端地址
    timeout: 5000
})

// 请求拦截器
request.interceptors.request.use(
    config => {
        const token = localStorage.getItem('token')
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`
        }
        return config
    },
    error => {
        return Promise.reject(error)
    }
)

// 响应拦截器
request.interceptors.response.use(
    response => {
        const res = response.data
        if (res.code !== 200) {
            message.error(res.msg || '请求失败')
            return Promise.reject(new Error(res.msg || '请求失败'))
        }
        return res
    },
    error => {
        message.error(error.message)
        return Promise.reject(error)
    }
)

export default request 