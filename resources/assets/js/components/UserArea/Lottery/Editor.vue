<template>
  <el-form :model="form" status-icon label-width="160px" >
    <el-form-item label="抽選賞品コード">
      <el-input :disabled="form.code !== ''" placeholder="example_lottery_code" v-model="form.code"/>
    </el-form-item>
    <el-form-item label="抽選賞品名">
      <el-input placeholder="サンプル賞品プレゼント" v-model="form.name"/>
    </el-form-item>
    <el-form-item label="賞品総数">
      <el-input placeholder="100" v-model="form.total">
        <template slot="append">個</template>
      </el-input>
    </el-form-item>
    <el-form-item label="本日の賞品残り数">
      {{form.remaining}} 個
    </el-form-item>
    <el-form-item label="当選制限数">
      <el-input placeholder="10" v-model="form.limit">
        <template slot="append">個</template>
      </el-input>
    </el-form-item>
    <el-form-item  label="当選制限緩和の時間">
      <el-select v-model="form.daily_increment_time" placeholder="0時">
        <el-option
          v-for="item in dailyIncrementTimeOptions"
          :key="item.value"
          :label="item.label"
          :value="item.value">
        </el-option>
      </el-select>
    </el-form-item>
    <el-form-item label="一日ごとの制限緩和数">
      <el-input :span="8" placeholder="10" v-model="form.daily_increment">
        <template slot="append">個</template>
      </el-input>
    </el-form-item>
    <el-form-item label="応募開始日時">
      <el-date-picker
        v-model="form.start_date"
        type="datetime"
        placeholder="開始日時">
      </el-date-picker>
    </el-form-item>
    <el-form-item label="応募終了日時">
      <el-date-picker
        v-model="form.end_date"
        type="datetime"
        placeholder="終了日時">
      </el-date-picker>
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
      dailyIncrementTimeOptions: [],
      form: {
        code: '',
        name: '',
        total: 0,
        limit: 0,
        rate: 0.0,
        remaining: 0,
        daily_increment: 0,
        daily_increment_time: 0,
        start_date: '',
        end_date: ''
      }
    }
  },
  watch: {
    input: function (input) {
      this.form = Object.assign({}, this.input)
    }
  },
  mounted () {
    for(let i = 0; i < 24; i++) {
      this.dailyIncrementTimeOptions.push({ value: i, label: i + '時' })
    }
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
