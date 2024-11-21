<template>
  <div class="login-container">
    <form @submit.prevent="handleLogin">
      <div class="form-item">
        <label>用户名：</label>
        <input v-model="loginForm.username" type="text" placeholder="请输入用户名">
      </div>
      <div class="form-item">
        <label>密码：</label>
        <input v-model="loginForm.password" type="password" placeholder="请输入密码">
      </div>
      <div class="form-item">
        <button type="submit">登录</button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Login',
  data() {
    return {
      loginForm: {
        username: '',
        password: ''
      }
    }
  },
  methods: {
    async handleLogin() {
      try {
        const response = await axios.post('/api/login', this.loginForm)
        if (response.data.code === 200) {
          // 保存token
          localStorage.setItem('token', response.data.data.token)
          // 保存用户信息
          localStorage.setItem('user', JSON.stringify(response.data.data.user))
          // 跳转到首页
          this.$router.push('/')
        } else {
          alert(response.data.msg)
        }
      } catch (error) {
        console.error('登录失败：', error)
        alert('登录失败，请稍后重试')
      }
    }
  }
}
</script>

<style scoped>
.login-container {
  width: 400px;
  margin: 100px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 4px;
}
.form-item {
  margin-bottom: 20px;
}
.form-item label {
  display: block;
  margin-bottom: 5px;
}
.form-item input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}
.form-item button {
  width: 100%;
  padding: 10px;
  background: #409EFF;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.form-item button:hover {
  background: #66b1ff;
}
</style> 