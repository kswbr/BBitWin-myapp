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

export default {
  name: 'LotteryCreate',
  components: {
    Editor
  },
  data () {
    return {
      form: {
        code: '',
        name: '',
        total: 0,
        limit: 0,
        rate: 0.0,
        remaining: 0,
        start_date: '',
        update: {
            daily_increment: 0,
            daily_increment_time: 0
        },
        end_date: ''
      }
    }
  },
  mouted () {
    this.form = {}
  },
  methods: {
    save (form) {
      Axios.post('/api/campaigns/' + this.$route.params.campaignId + '/lotteries', form).then((res) => {
        this.$router.push('.')
        console.log(res)
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
