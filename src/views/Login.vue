<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { message } from 'ant-design-vue'
import request from '@/utils/request'

const router = useRouter()
const formState = reactive({
    username: '',
    password: ''
})

const onFinish = async (values) => {
    try {
        const { data } = await request({
            url: '/user/login',
            method: 'post',
            data: values
        })
        
        // 保存token
        localStorage.setItem('token', data.token)
        message.success('登录成功')
        router.push('/')
    } catch (error) {
        console.error('登录失败:', error)
    }
}
</script> 