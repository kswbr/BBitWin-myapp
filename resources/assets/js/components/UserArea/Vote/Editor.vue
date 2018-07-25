<template>
  <el-form :model="form" status-icon label-width="160px" >
    <el-form-item label="投票イベントコード">
      <el-input :disabled="input.code !== ''" placeholder="example_vote_code" v-model="form.code"/>
    </el-form-item>
    <el-form-item label="投票イベント名">
      <el-input placeholder="サンプル投票イベント" v-model="form.name"/>
    </el-form-item>
    <el-form-item label="選択肢">
      <el-input type="textarea" rows="10" placeholder="choice_1,選択肢
choice_2,選択肢B
choice_3,選択肢C
choice_4,選択肢D" v-model="form.choice"></el-input>
    </el-form-item>
    <el-form-item label="公開状態">
      <el-checkbox v-model="form.active">公開状態にする</el-checkbox>
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
        choice: '',
        active: false,
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
