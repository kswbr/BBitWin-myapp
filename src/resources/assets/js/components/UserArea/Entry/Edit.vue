<template>
  <el-container id="entryEdit" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../../../.'}">キャンペーン</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../../.'}" >抽選賞品</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}">応募状況</el-breadcrumb-item>
          <el-breadcrumb-item >{{form.id}}</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Edit Entry <small >応募状態編集 </small></h2>
          </el-col>
        </el-header >
      </el-row>
      <el-row>
        <el-col :offset="1" :span="21">
          <Editor :apiPath="apiPath" :input="form" :save="save" :remove="remove" />
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
  name: 'EntryEdit',
  components: {
    Editor
  },
  data () {
    return {
      form: {
        id:0,
        player_id:0,
        state:0,
        created_at:0,
        updated_at:0,
      }
    }
  },
  mounted () {
    this.fetch()
  },
  methods: {
    fetch () {
      Axios.get(this.apiPath + '/' + this.$route.params.id).then((res) => {
        this.form = Object.assign({}, this.form, res.data)
      }).catch((e) => (console.error(e)))
    },
    save (form) {
      Axios.patch(this.apiPath + '/' + this.$route.params.id, form).then((res) => {
        this.$router.push('.')
        console.log(res)
        this.$store.commit(types.FORM_VALIDATION_SUCCESS, {
          message: '応募が更新されました'
        })
      }).catch((e) => (console.error(e)))
    },
    remove () {
      Axios.delete(this.apiPath + '/' + this.$route.params.id).then((res) => {
        this.$router.push('.')
        console.log(res)
        this.$store.commit(types.FORM_VALIDATION_SUCCESS, {
          message: '応募が削除されました'
        })
      }).catch((e) => (console.error(e)))
    }
  },
  computed: {
    apiPath: function () {
        return '/api/campaigns/' + this.$route.params.campaignId + '/lotteries/' + this.$route.params.lotteryId + '/entries'
    }
  }
}
</script>

<style>
</style>
