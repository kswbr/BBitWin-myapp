<template>
  <el-form :model="form" status-icon label-width="180px" >
    <el-form-item label="ユーザー名">
      <el-input placeholder="ユーザー名" v-model="form.name"/>
    </el-form-item>
    <el-form-item label="メールアドレス">
      <el-input placeholder="examplemail@dummymail.com" v-model="form.email"/>
    </el-form-item>
    <el-form-item >
      <el-button type="default" @click="() => (this.$router.push('/admin/userarea'))">戻る</el-button>
      <el-button type="primary" @click="submitForm()">保存</el-button>
      <el-button type="text" @click="() => (this.$router.push(this.$route.params.id + '/change_password'))">パスワード変更</el-button>
    </el-form-item>
  </el-form>
</template>

<script>

import { mapGetters } from 'vuex'
import * as types from '../../../store/mutation-types.js'
import _ from 'lodash'

export default {
  name: 'MyAccountEditor',
  props: {
    input: Object,
    save: Function
  },
  data () {
    return {
      roleList: [],
      form: {
        mode:'',
        name:'',
        email:'',
      }
    }
  },
  watch: {
    input: function (input) {
      this.form = Object.assign({}, this.input)
    }
  },
  mounted () {
  },
  methods: {
    submitForm () {
      if (!window.confirm('データを保存しますか？')) {
        return
      }
      this.save(this.form)
    }
  }
}
</script>

<style>
</style>
