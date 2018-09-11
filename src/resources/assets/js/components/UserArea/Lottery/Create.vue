<template>
  <el-container id="lotteryCreate" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../../.'}">キャンペーン</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}" >抽選賞品</el-breadcrumb-item>
          <el-breadcrumb-item>新規作成</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Create Lottery <small >抽選賞品 新規作成 </small></h2>
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
  name: 'LotteryCreate',
  components: {
    Editor
  },
  data () {
    return {
      form: {
        active: false,
        code: '',
        name: '',
        total: 0,
        limit: 0,
        rate: 0.0,
        remaining: 0,
        daily_increment: 0,
        daily_increment_time: 0,
        start_date: '',
        end_date: '',
        order: 0
      }
    }
  },
  mouted () {
    this.form = {}
  },
  methods: {
    save (form) {
      Axios.post('/api/campaigns/' + this.$route.params.campaignId + '/lotteries', form).then((res) => {
        console.log(res)
        this.$router.push('.')
        this.$store.commit(types.FORM_VALIDATION_SUCCESS, {
          message: '賞品が作成されました'
        })
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
