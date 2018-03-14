<template>
  <el-container id="campaignCreate" >
    <el-main>
      <el-row>
        <el-col :offset="1" :span="21">
          <el-header >
            <h2 class="h2">Edit Campaign <small >キャンペーン編集 </small></h2>
          </el-header >
        </el-col>
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
        limited_days: 1
      }
    }
  },
  mounted () {
    this.fetch()
  },
  methods: {
    fetch () {
      Axios.get('/api/campaigns/' + this.$route.params.id).then((res) => {
        this.form = Object.assign({}, res.data)
      }).catch((e) => (console.error(e)))
    },
    save (form) {
      Axios.patch('/api/campaigns/' + this.$route.params.id, form).then((res) => {
        this.$router.push('.')
        console.log(res)
      }).catch((e) => (console.error(e)))
    },
    remove () {
      Axios.delete('/api/campaigns/' + this.$route.params.id).then((res) => {
        this.$router.push('.')
        console.log(res)
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
