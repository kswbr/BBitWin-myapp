<template>
  <el-container id="serialCreate" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}">シリアルナンバー抽選</el-breadcrumb-item>
          <el-breadcrumb-item>{{form.name}}</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
          <el-col :offset="1" :span="21">
            <h2 class="h2">Edit Serial <small >シリアルナンバー抽選編集 </small></h2>
          </el-col>
        </el-header >
      </el-row>
      <el-row>
        <el-col :offset="1" :span="21" v-loading="loading">
          <Editor :input="form" :csv="getCSV" :save="save" :remove="remove" />
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>

import Axios from 'axios'
import Editor from './Editor.vue'
import * as types from '../../../store/mutation-types.js'
import FileDownload from 'js-file-download'

export default {
  name: 'SerialEdit',
  components: {
    Editor
  },
  data () {
    return {
      form: {
        code: '',
        name: '',
        total: '',
        winner_total: ''
      },
      loading: true
    }
  },
  mounted () {
    this.fetch()
  },
  methods: {
    fetch () {
      this.loading = true
      return Axios.get('/api/serials/' + this.$route.params.id).then((res) => {
        console.log(res)
        this.form = Object.assign({}, res.data)
        this.loading = false
        return res
      }).catch((e) => (console.error(e)))
    },
    save (form) {
      this.loading = true
      Axios.patch('/api/serials/' + this.$route.params.id, form).then((res) => {
        return Axios.post('/api/serials/' + this.$route.params.id + '/migrate')
      }).then((res) => {
        return this.fetch()
      }).then((res) => {
        console.log(res)
        this.$store.commit(types.FORM_VALIDATION_SUCCESS, {
          message: 'シリアルナンバー抽選が更新されました'
        })
        this.loading = false
      }).catch((e) => {
        this.loading = false
        console.error(e)
      })
    },
    getCSV (form) {
      Axios.get('/api/serials/' + this.$route.params.id + '/csv').then((res) => {
        FileDownload(res.data, 'serial_numver_' + this.$route.params.id + '.csv');
      });
    },
    remove () {
      Axios.delete('/api/serials/' + this.$route.params.id).then((res) => {
        this.$router.push('.')
        console.log(res)
        this.$store.commit(types.FORM_VALIDATION_SUCCESS, {
          message: 'シリアルナンバー抽選が削除されました'
        })
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
