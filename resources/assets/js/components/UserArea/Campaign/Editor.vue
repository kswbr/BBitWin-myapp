<template>
  <el-form :model="form" status-icon label-width="160px" >
    <el-form-item label="キャンペーンコード">
      <el-input :disabled="form.code !== ''" placeholder="example_campaign_code" v-model="form.code"/>
    </el-form-item>
    <el-form-item label="キャンペーン名">
      <el-input placeholder="サンプルキャンペーン" v-model="form.name"/>
    </el-form-item>
    <el-form-item label="当選時の有効期限日数">
      <el-input placeholder="1" v-model="form.limited_days">
        <template slot="append">日</template>
      </el-input>
    </el-form-item>
    <el-form-item >
      <el-button type="default" @click="() => (this.$router.push('.'))">戻る</el-button>
      <el-button type="primary" @click="submitForm()">保存</el-button>
      <el-button v-if="remove" type="text" @click="removeItem()">削除</el-button>
    </el-form-item>
  </el-form>
</template>

<script>

export default {
  name: 'CampaignEditor',
  props: {
    input: Object,
    save: Function,
    remove: Function
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
