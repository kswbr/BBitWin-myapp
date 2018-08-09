<template>
  <el-container id="userCreate" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}" >ユーザー一覧</el-breadcrumb-item>
          <el-breadcrumb-item>新規作成</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Create User <small >ユーザー 新規作成 </small></h2>
          </el-col>
        </el-header >
      </el-row>
      <el-row>
        <el-col :offset="1" :span="21">
          <Editor :input="form" :save="save" />
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>

import Axios from 'axios'
import Editor from './Editor.vue'

export default {
  name: 'UserCreate',
  components: {
    Editor
  },
  data () {
    return {
      form: {
        mode:'Create',
        name:'',
        email:'',
        password:'',
        password_confirmation:'',
        allow_campaign: false,
        allow_vote: false,
        allow_user: false,
        allow_over_project: false,
        role: 0
      }
    }
  },
  mouted () {
    this.form = {}
  },
  methods: {
    save (form) {
      Axios.post('/api/users', form).then((res) => {
        this.$router.push('.')
        console.log(res)
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
