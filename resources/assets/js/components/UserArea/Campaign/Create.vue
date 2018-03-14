<template>
  <el-container id="campaignCreate" >
    <el-main>
      <el-row>
        <el-col :offset="1" :span="21">
          <el-header >
            <h2 class="h2">Create Campaign <small >キャンペーン新規作成 </small></h2>
          </el-header >
        </el-col>
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
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
