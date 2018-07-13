<template>
  <el-container id="campaignCreate" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../../../'}">キャンペーン</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../'}" >抽選賞品</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}">応募状況</el-breadcrumb-item>
          <el-breadcrumb-item >{{form.id}}</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Edit Campaign <small >キャンペーン編集 </small></h2>
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
  name: 'CampaignEdit',
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
        remain: 0,
        start_date: '',
        update: {
            daily_increment: 0,
            daily_increment_time: 0
        },
        end_date: ''
      }
    }
  },
  mounted () {
    this.fetch()
  },
  methods: {
    fetch () {
      Axios.get('/api/campaigns/' + this.$route.params.campaignId + '/lotteries/' + this.$route.params.id).then((res) => {
        this.form = Object.assign({}, this.form, res.data)
      }).catch((e) => (console.error(e)))
    },
    save (form) {
      Axios.patch('/api/campaigns/' + this.$route.params.campaignId + '/lotteries/' + this.$route.params.id, form).then((res) => {
        this.$router.push('.')
        console.log(res)
      }).catch((e) => (console.error(e)))
    },
    remove () {
      Axios.delete('/api/campaigns/' + this.$route.params.campaignId + '/lotteries/' + this.$route.params.id).then((res) => {
        this.$router.push('.')
        console.log(res)
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
