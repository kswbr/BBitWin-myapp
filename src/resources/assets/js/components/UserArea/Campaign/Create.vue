<template>
  <el-container id="campaignCreate" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}">キャンペーン</el-breadcrumb-item>
          <el-breadcrumb-item>新規作成</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Create Campaign <small >キャンペーン新規作成 </small></h2>
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
  name: 'CampaignCreate',
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
      Axios.post('/api/campaigns', form).then((res) => {
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
