<template>
  <el-container id="lotteryEdit" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../../.'}">キャンペーン</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}" >抽選賞品</el-breadcrumb-item>
          <el-breadcrumb-item>{{form.name}}</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Edit Lottery <small >抽選賞品編集 </small></h2>
          </el-col>
          <el-col :offset="20" :span="1">
            <InfoModal :campaignId="this.$route.params.campaignId" :lotteryId="this.$route.params.id"/>
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
import InfoModal from './InfoModal.vue'
import * as types from '../../../store/mutation-types.js'

export default {
  name: 'LotteryEdit',
  components: {
    Editor,
    InfoModal
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
        console.log(res)
        this.$router.push('.')
        this.$store.commit(types.FORM_VALIDATION_SUCCESS, {
          message: '賞品が更新されました'
        })
      }).catch((e) => (console.error(e)))
    },
    remove () {
      Axios.delete('/api/campaigns/' + this.$route.params.campaignId + '/lotteries/' + this.$route.params.id).then((res) => {
        this.$router.push('.')
        console.log(res)
        this.$store.commit(types.FORM_VALIDATION_SUCCESS, {
          message: '賞品が削除されました'
        })
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
