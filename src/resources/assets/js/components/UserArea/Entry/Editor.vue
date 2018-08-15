<template>
  <el-form :model="form" status-icon label-width="160px" >
    <el-form-item label="お問い合わせID">
      {{form.id}}
    </el-form-item>
    <el-form-item label="ユーザーID">
      {{form.player_id}}
    </el-form-item>
    <el-form-item label="状態">
      <el-select v-model="form.state" placeholder="0時">
        <el-option
          v-for="item in stateLabels"
          :key="item.value"
          :label="item.label"
          :value="item.value">
        </el-option>
      </el-select>
    </el-form-item>
    <el-form-item label="応募日時">
      {{form.created_at}}
    </el-form-item>
    <el-form-item label="更新日時">
      {{form.updated_at}}
    </el-form-item>
    <el-form-item >
      <el-button plain type="default" @click="() => (this.$router.push('.'))">戻る</el-button>
      <el-button type="primary" @click="submitForm()">保存</el-button>
      <el-button v-if="remove" type="text" @click="removeItem()">削除</el-button>
    </el-form-item>
  </el-form>
</template>

<script>

import Axios from 'axios'
import _ from 'lodash'

export default {
  name: 'EntryEditor',
  props: {
    input: Object,
    apiPath: String,
    save: Function,
    remove: Function
  },
  data () {
    return {
      stateLabels: [],
      form: {}
    }
  },
  watch: {
    input: function (input) {
      this.form = Object.assign({}, this.input)
    }
  },
  mounted () {
    Axios.get(this.apiPath + '/state_list').then((res) => {
      this.stateLabels = _.map(res.data, (data,i) => { return {label: data.label, value: i} })
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
