<template>
  <el-container id="userChangePassword" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../.'}" >ユーザー一覧</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}" >ユーザー編集</el-breadcrumb-item>
          <el-breadcrumb-item>パスワード変更</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Change Password <small >パスワード変更 </small></h2>
          </el-col>
        </el-header >
      </el-row>
      <el-row>
        <el-col :offset="1" :span="21">
          <el-form :model="form" status-icon label-width="180px" >
            <el-form-item label="以前のパスワード" >
              <el-input type="password" v-model="form.old_password" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="パスワード">
              <el-input type="password" v-model="form.password" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="パスワード確認" >
              <el-input type="password" v-model="form.password_confirmation" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item >
              <el-button type="default" @click="() => (this.$router.push('.'))">戻る</el-button>
              <el-button type="primary" @click="save()">保存</el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>

import Axios from 'axios'

export default {
  name: 'UserEdit',
  data () {
    return {
      form: {
        old_password:'',
        password_confirmation:'',
        password:'',
      }
    }
  },
  mounted () {
  },
  methods: {
    save () {
      if (!window.confirm('パスワードを変更しますか？')) {
        return
      }
      Axios.patch('/api/users/' + this.$route.params.id + '/change_password', this.form).then((res) => {
        this.$router.push('.')
        console.log(res)
      }).catch((e) => (console.error(e)))
    },
  }
}
</script>

<style>
</style>
