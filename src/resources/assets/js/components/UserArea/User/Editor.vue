<template>
  <el-form :model="form" status-icon label-width="180px" >
    <el-form-item label="ユーザー名">
      <el-input placeholder="ユーザー名" v-model="form.name"/>
    </el-form-item>
    <el-form-item label="メールアドレス">
      <el-input placeholder="examplemail@dummymail.com" v-model="form.email"/>
    </el-form-item>
    <el-form-item label="パスワード" v-if="input.mode == 'Create'">
      <el-input type="password" v-model="form.password" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="パスワード確認" v-if="input.mode == 'Create'">
      <el-input type="password" v-model="form.password_confirmation" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="キャンペーン機能">
      <el-checkbox v-model="form.allow_campaign" :value="1">許可する</el-checkbox>
    </el-form-item>
    <el-form-item label="投票機能">
      <el-checkbox v-model="form.allow_vote">許可する</el-checkbox>
    </el-form-item>
    <el-form-item label="ユーザー管理">
      <el-checkbox v-model="form.allow_user">許可する</el-checkbox>
    </el-form-item>
    <el-form-item label="全プロジェクトにログイン">
      <el-checkbox v-model="form.allow_over_project">許可する</el-checkbox>
    </el-form-item>
    <el-form-item label="機能制限">
      <el-select v-model="form.role" >
        <el-option
          v-for="item in roleList"
          :key="item.value"
          :label="item.label"
          :value="item.value">
        </el-option>
      </el-select>
    </el-form-item>
    <el-form-item >
      <el-button type="default" @click="() => (this.$router.push('.'))">戻る</el-button>
      <el-button type="primary" @click="submitForm()">保存</el-button>
      <el-button v-if="remove" type="text" @click="() => (this.$router.push(this.$route.params.id + '/change_password'))">パスワード変更</el-button>
      <el-button :disabled="!allowDelete" v-if="remove && !this.$route.meta.myAccount" type="text" @click="removeItem()">削除</el-button>
    </el-form-item>
  </el-form>
</template>

<script>

import { mapGetters } from 'vuex'
import * as types from '../../../store/mutation-types.js'
import Axios from 'axios'
import _ from 'lodash'

export default {
  name: 'UserEditor',
  computed: {
    ...mapGetters(['allowDelete'])
  },
  props: {
    input: Object,
    save: Function,
    remove: Function
  },
  data () {
    return {
      roleList: [],
      form: {
        mode:'',
        name:'',
        email:'',
        allow_campaign: false,
        allow_vote: false,
        allow_user: false,
        allow_over_project: false,
        role: 0
      }
    }
  },
  watch: {
    input: function (input) {
      this.form = Object.assign({}, this.input,{
        allow_campaign: Boolean(this.input.allow_campaign),
        allow_vote: Boolean(this.input.allow_vote),
        allow_user: Boolean(this.input.allow_user),
        allow_over_project: Boolean(this.input.allow_over_project),
      })
    }
  },
  mounted () {
    Axios.get('/api/users/role_list').then((res) => {
      this.roleList = _.map(res.data, (data,i) => { return {label: data.label, value: i} })
    })
  },
  methods: {
    submitForm () {
      if (!window.confirm('データを保存しますか？')) {
        return
      }
      this.save(this.form)
    },
    removeItem () {
      if (!window.confirm('データを削除しますか？')) {
        return
      }
      this.remove()
    }
  }
}
</script>

<style>
</style>
