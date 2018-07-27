<template>
  <el-container id="voteCreate" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}" >投票イベント</el-breadcrumb-item>
          <el-breadcrumb-item>{{form.name}}</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Edit Vote <small >投票イベント編集 </small></h2>
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
  name: 'voteEdit',
  components: {
    Editor
  },
  data () {
    return {
      form: {
        code: '',
        name: '',
        choice: '',
        active: false,
        start_date: '',
        end_date: ''
      }
    }
  },
  mounted () {
    this.fetch()
  },
  methods: {
    fetch () {
      Axios.get('/api/votes/' + this.$route.params.id).then((res) => {
        this.form = Object.assign({}, this.form, res.data)
      }).catch((e) => (console.error(e)))
    },
    save (form) {
      Axios.patch('/api/votes/' + this.$route.params.id, form).then((res) => {
        this.$router.push('.')
        console.log(res)
      }).catch((e) => (console.error(e)))
    },
    remove () {
      Axios.delete('/api/votes/' + this.$route.params.id).then((res) => {
        this.$router.push('.')
        console.log(res)
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
