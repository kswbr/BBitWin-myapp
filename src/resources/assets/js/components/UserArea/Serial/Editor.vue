<template>
  <el-form :model="form" status-icon label-width="160px" >
    <el-form-item label="抽選名">
      <el-input placeholder="シリアルナンバーキャンペーン" v-model="form.name"/>
    </el-form-item>
    <el-form-item label="コード">
      <el-input :disabled="input.code !== ''" placeholder="example_campaign_code" v-model="form.code"/>
    </el-form-item>
    <el-form-item label="発行ナンバー総数">
      <el-input placeholder="0" v-model="form.total"/>
    </el-form-item>
    <el-form-item label="当選数">
      <el-input :disabled="input.code === ''" placeholder="0" v-model="form.winner_total">
      </el-input>
    </el-form-item>
    <el-form-item >
      <el-button type="default" plain @click="() => (this.$router.push('.'))">戻る</el-button>
      <el-button type="primary" @click="submitForm()">保存</el-button>
      <el-button type="success" v-if="csv" @click="getCSV()">CSVダウンロード</el-button>
      <el-button :disabled="!allowDelete" v-if="remove" type="text" @click="removeItem()">削除</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import { mapGetters } from 'vuex'
import * as types from '../../../store/mutation-types.js'
import Axios from 'axios'
import _ from 'lodash'

export default {
  name: 'SerialEditor',
  computed: {
    ...mapGetters(['allowDelete'])
  },
  props: {
    input: Object,
    save: Function,
    csv: Function,
    remove: Function
  },
  data () {
    return {
      campaigns: [],
      form: {
        code: '',
        name: '',
        total: '',
        winner_total: ''
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
    },
    getCSV () {
      if (!window.confirm('データをダウンロードしますか？')) {
        return
      }
      this.csv()
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
