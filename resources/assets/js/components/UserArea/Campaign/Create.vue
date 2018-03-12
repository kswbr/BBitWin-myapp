<template>
  <el-container id="campaignCreate" >
    <el-main>
      <el-row>
        <el-col :offset="1" :span="21">
          <el-header >
            <h2>キャンペーン新規作成</h2>
          </el-header >
        </el-col>
      </el-row>
      <el-row>
      <el-col :offset="1" :span="21">
        <el-form :model="form" status-icon label-width="160px" >
          <el-form-item label="キャンペーンコード">
            <el-input  placeholder="example_campaign_code" v-model="form.code"/>
          </el-form-item>
          <el-form-item  label="キャンペーン名">
            <el-input placeholder="サンプルキャンペーン" v-model="form.name"/>
          </el-form-item>
          <el-form-item label="当選時の有効期限日数">
            <el-input placeholder="1" v-model="form.limited_days"/>
          </el-form-item>
          <el-form-item >
            <el-button type="primary" @click="submitForm('form')">Create</el-button>
          </el-form-item>
        </el-form>
      </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>

export default {
  name: 'CampaignCreate',
  data () {
    return {
      form: {
        code: '',
        name: '',
        limited_days: 1
      }
    }
  }
}
</script>

<style>
</style>
