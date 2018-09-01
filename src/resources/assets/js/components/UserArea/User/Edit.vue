<template>
  <el-container id="userEdit" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}" >ユーザー一覧</el-breadcrumb-item>
          <el-breadcrumb-item>{{form.name}}</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Edit User <small >ユーザー編集 </small></h2>
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

import { mapState } from 'vuex'
import * as types from '../../../store/mutation-types.js'
import Axios from 'axios'
import Editor from './Editor.vue'

export default {
  name: 'UserEdit',
  components: {
    Editor
  },
  data () {
    return {
      form: {
        mode:'Edit',
        name:'',
        email:'',
        allow_campaign: false,
        allow_vote: false,
        allow_user: false,
        allow_over_project: false,
        allow_serial_campaign: false,
        role: 0
      }
    }
  },
  mounted () {
    this.fetch()
  },
  computed: {
    ...mapState(['user'])
  },
  methods: {
    fetch () {
      Axios.get('/api/users/' + this.$route.params.id).then((res) => {
        this.form = Object.assign({}, this.form, res.data)
      }).catch((e) => (console.error(e)))
    },
    save (form) {
      Axios.patch('/api/users/' + this.$route.params.id, form).then((res) => {
        Axios.get('/api/user/info').then((res) => {
          this.$store.commit(types.FETCH_USER, res.data)
          this.$router.push('.')
          this.$store.commit(types.FORM_VALIDATION_SUCCESS, {
            message: 'ユーザーが更新されました'
          })
        })
      }).catch((e) => (console.error(e)))
    },
    remove () {
      Axios.delete('/api/users/' + this.$route.params.id).then((res) => {
        if (this.$route.params.id == this.user.id) {
          this.$store.commit(types.LOGGED_OUT)
          this.$store.commit(types.FORM_VALIDATION_SUCCESS, {
            message: 'あなたのユーザーが削除されたのでログアウトします'
          })
        } else {
          this.$router.push('.')
          this.$store.commit(types.FORM_VALIDATION_SUCCESS, {
            message: 'ユーザーが削除されました'
          })
        }
      }).catch((e) => (console.error(e)))
    }
  }
}
</script>

<style>
</style>
