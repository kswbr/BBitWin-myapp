<template>
  <el-container id="campaignCreate" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}" >ユーザー一覧</el-breadcrumb-item>
          <el-breadcrumb-item>{{form.name}}</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Edit User <small >ユーザー編集 </small></h2>
          </el-col>
        </el-header >
      </el-row>
      <el-row>
        <el-col :offset="1" :span="21">
          <Editor :input="form" :save="save" :remove="remove" />
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>

import Axios from 'axios'
import Editor from './Editor.vue'

export default {
  name: 'UserEdit',
  components: {
    Editor
  },
  data () {
    return {
      form: {
        mode:'Edit',
        name:'',
        email:'',
        allow_campaign: false,
        allow_vote: false,
        allow_user: false,
        allow_over_project: false,
        role: 0
      }
    }
  },
  mounted () {
    this.fetch()
  },
  methods: {
    fetch () {
      Axios.get('/api/users/' + this.$route.params.id).then((res) => {
        this.form = Object.assign({}, this.form, res.data)
      }).catch((e) => (console.error(e)))
    },
    save (form) {
      Axios.patch('/api/users/' + this.$route.params.id, form).then((res) => {
        this.$router.push('.')
        console.log(res)
      }).catch((e) => (console.error(e)))
    },
    remove () {
      Axios.delete('/api/users/' + this.$route.params.id).then((res) => {
        this.$router.push('.')
        console.log(res)
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
