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
            <el-input name="username" placeholder="ユーザー名又はメールアドレス" v-model="form.username"/>
          </el-form-item>
          <el-form-item >
            <el-input name="password" type="password" v-model="form.password" auto-complete="off"/>
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
import * as types from '../store/mutation-types'

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

      new Promise((resolve, reject) => {
        return this.$store.dispatch('requestStart',{ label: 'LOGIN', checkDuplication: true }).then(res => {
          return Axios.post('/admin', this.form)
        }).then((res) => {
          this.$store.commit(types.LOGGED_IN)
          this.$store.commit(types.API_REQUEST_END, 'LOGIN_SUCCEED')
          return resolve()
        }).catch(e => this.$state.commit(types.API_REQUEST_FAILED, 'LOGIN_FAILED', e))
      })
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
