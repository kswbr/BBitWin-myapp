<template>
  <el-container id="campaign" >
    <el-main>
      <el-row>
        <el-col :offset="1" :span="21">
          <el-header >
            <h2>キャンペーン一覧</h2>
          </el-header >
        </el-col>
      </el-row>

      <el-row>
        <el-col :offset="20" :span="2" >
          <el-button type="success">新規作成</el-button>
        </el-col>
      </el-row>

      <el-row>
        <el-col :offset="1" :span="21">
          <el-table :data="tableData" >
            <el-table-column prop="date" label="Date" width="140"/>
            <el-table-column prop="name" label="Name" width="120"/>
            <el-table-column prop="address" label="Address"/>
          </el-table>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>

export default {
  name: 'Campaign',
  data () {
    return {
      tableData: []
    }
  }
}
</script>

<style>
</style>
