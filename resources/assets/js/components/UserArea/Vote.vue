<template>
  <el-container id="vote" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item>投票管理</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Votes <small >投票一覧 </small></h2>
          </el-col>
          <el-col :offset="20" :span="2">
            <el-button type="success" @click="() => (this.$router.push('votes/create'))">
              新規作成
            </el-button>
          </el-col>
        </el-header >
      </el-row>
      <Pagination :handleCurrentChange="handleCurrentChange" :pagination="pagination" />
      <el-row>
        <el-col :offset="2" :span="20">
          <el-table v-loading="loading" :data="tableData" >
            <el-table-column prop="code" label="コード" width="140"/>
            <el-table-column prop="name" label="投票イベント名称" />
            <el-table-column prop="start_date" label="開始日時" width="100"/>
            <el-table-column prop="end_date" label="終了日時" width="100"/>
            <el-table-column
              fixed="right"
              label="操作"
              width="140">
              <template slot-scope="scope">
                <el-button type="text" @click="editRow(scope.row)">編集</el-button>
                <el-button  type="text" @click="showChart(scope.row)">グラフ<i class="el-icon-arrow-right"></i> </el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-col>
      </el-row>
      <Pagination :handleCurrentChange="handleCurrentChange" :pagination="pagination" />
    </el-main>
  </el-container>
</template>

<script>
import Axios from 'axios'
import Pagination from './Pagination'

export default {
  name: 'Vote',
  components: {
    Pagination
  },
  data () {
    return {
      pagination: {},
      tableData: [],
      loading: true
    }
  },
  mounted () {
    this.getList()
  },
  methods: {
    getList () {
      const page = this.$route.query.page
      this.loading = true
      Axios.get('/api/votes', { params:{page}}).then((res) => {
        this.pagination = Object.assign({},this.pagination,res.data)
        this.tableData = res.data.data
        this.loading = false
      })
    },
    handleCurrentChange (page) {
        this.$router.push({query: {page}})
        this.getList()
    },
    showChart (item) {
      this.$router.push('votes/' + item.id + '/chart')
    },
    editRow (item) {
      this.$router.push('votes/' + item.id)
    }
  }
}
</script>

