<template>
  <div id="login">
    <el-row >
      <h1>BbmInstantWin</h1>
      <h2>Login</h2>
      <el-col
        :span="12"
        :offset="5">
        <el-form :model="form" status-icon label-width="120px" >
          <el-form-item >
            <el-input placeholder="ユーザー名又はメールアドレス" v-model="form.username"/>
          </el-form-item>
          <el-form-item >
            <el-input type="password" v-model="form.password" auto-complete="off"/>
          </el-form-item>
          <el-form-item >
            <el-button type="primary" @click="submitForm('form')">Login</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>

import Axios from 'axios'

export default {
  name: 'Login',
  data () {
    return {
      form: {
        username: '',
        password: ''
      }
    }
  },
  mounted: function () {
    console.log('mouted')
  },
  methods: {
    submitForm (name) {
      Axios.post('/admin', this.form).then((res) => {
        localStorage.setItem('Authorization.access_token', res.data.access_token)
        localStorage.setItem('Authorization.refresh_token', res.data.refresh_token)

        location.reload()
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
#login {
  margin-top: 60px;
  text-align: center;
}

</style>
