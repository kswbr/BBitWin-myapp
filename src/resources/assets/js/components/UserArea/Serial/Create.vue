<template>
  <el-container id="serialCreate" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}">シリアルナンバー抽選一覧</el-breadcrumb-item>
          <el-breadcrumb-item>新規作成</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Create Serial Number Campaign <small >シリアルナンバー抽選 新規作成 </small></h2>
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
import * as types from '../../../store/mutation-types.js'

export default {
  name: 'SerialCreate',
  components: {
    Editor
  },
  data () {
    return {
      form: {
        code: '',
        name: '',
        limited_days: 1
      }
    }
  },
  mouted () {
    this.form = {}
  },
  methods: {
    save (form) {
      Axios.post('/api/serials', form).then((res) => {
        this.$router.push('.')
        console.log(res)
        this.$store.commit(types.FORM_VALIDATION_SUCCESS, {
          message: 'キャンペーンが作成されました'
        })
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
