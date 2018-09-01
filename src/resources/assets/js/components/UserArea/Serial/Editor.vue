<template>
  <el-form :model="form" status-icon label-width="160px" >
    <el-form-item label="抽選名">
      <el-input placeholder="シリアルナンバーキャンペーン" v-model="form.name"/>
    </el-form-item>
    <el-form-item label="親キャンペーン名">
      <el-select v-model="form.campaign_code" >
        <el-option
          v-for="item in campaigns"
          :key="item.value"
          :label="item.label"
          :value="item.value">
        </el-option>
      </el-select>
    </el-form-item>
    <el-form-item label="シリアルナンバー総数">
      <el-input v-model="form.total">
      </el-input>
    </el-form-item>
    <el-form-item >
      <el-button type="default" plain @click="() => (this.$router.push('.'))">戻る</el-button>
      <el-button type="primary" @click="submitForm()">保存</el-button>
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
    remove: Function
  },
  data () {
    return {
      campaigns: [],
      form: {
        campaign_code: '',
        name: '',
        total: ''
      }
    }
  },
  watch: {
    input: function (input) {
      this.form = Object.assign({}, this.input)
    }
  },
  mounted () {
    Axios.get('/api/campaigns/has_not_serial').then((res) => {
      this.campaigns = _.map(res.data, (data,i) => { return {label: data.name, value: data.code} })
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
