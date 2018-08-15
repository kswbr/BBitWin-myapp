<template>
  <div id="login">
    <el-row >
      <h1>BbmInstantWin</h1>
      <p>インスタントウィンサンプルプロジェクト</p >
    </el-row>
    <el-row >
      <el-col
        :span="10"
        :offset="7">

        <el-alert
          v-if="invalid"
          title="アカウント情報に誤りがあります"
          type="error"/>
      </el-col>
    </el-row>
    <el-row >
      <el-col
        :span="10"
        :offset="7">
        <el-form :model="form" >
          <el-form-item >
            <el-input name="username" placeholder="メールアドレス" v-model="form.username"/>
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
import { mapState } from 'vuex'

export default {
  name: 'Login',
  data () {
    return {
      invalid: false,
      form: {
        username: '',
        password: ''
      }
    }
  },
  computed: {
    ...mapState(['loggedIn','user'])
  },
  mounted: function () {
    if (this.loggedIn) {
      Axios.get('/api/user/info').then((res) => {
        this.$store.commit(types.FETCH_USER, res.data)
        this.$router.push('/admin/userarea')
      })
    }
    return
  },
  methods: {
    submitForm (name) {
      Axios.post('/admin', this.form).then((res) => {
        this.invalid = false
        this.$store.commit(types.LOGGED_IN, res.data)
      }).catch(error => {
        this.$store.commit(types.API_REQUEST_FAILED, { label: 'LOGIN_FAILED', error })
        this.invalid = true
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
