<template>
  <el-container id="campaign" >
    <el-main>
      <el-row>
          <el-header >
        <el-col :offset="1" :span="21">
            <h2 class="h2">Campaigns <small >キャンペーン一覧 </small></h2>
        </el-col>
        <el-col :offset="20" :span="2">
          <el-button type="success" @click="() => (this.$router.push('campaigns/create'))">
            新規作成
          </el-button>
        </el-col>
          </el-header >
      </el-row>

      <el-row>
        <el-col :offset="2" :span="20">
          <el-table :data="tableData" >
            <el-table-column prop="code" label="コード" width="140"/>
            <el-table-column prop="name" label="キャンペーン名称" width="300"/>
            <el-table-column prop="limited_days" label="当選時の有効日数"/>
            <el-table-column
              fixed="right"
              label="操作"
              width="130">
              <template slot-scope="scope">
                <el-button type="text" @click="editRow(scope.row)" >編集</el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
import Axios from 'axios'

export default {
  name: 'Campaign',
  data () {
    return {
      tableData: []
    }
  },
  mounted () {
    this.getList()
  },
  methods: {
    getList () {
      Axios.get('/api/campaigns', { page: 0 }).then((res) => {
        this.tableData = res.data.data
      }).catch((e) => (console.error(e)))
    },
    editRow (item) {
      this.$router.push('campaigns/' + item.id)
    }
  }
}
</script>

