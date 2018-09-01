<template>
  <el-container id="serial" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item>シリアルナンバー抽選</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Serial Number Campaigns <small >シリアルナンバー抽選一覧 </small></h2>
          </el-col>
          <el-col :offset="20" :span="2">
            <el-button :disabled="!allowCreate" type="success" @click="() => (this.$router.push('serials/create'))">
              新規作成
            </el-button>
          </el-col>
        </el-header >
      </el-row>
      <Pagination :handleCurrentChange="handleCurrentChange" :pagination="pagination" />
      <el-row>
        <el-col :offset="2" :span="20">
          <el-table v-loading="loading" :data="tableData" >
            <el-table-column prop="name" label="抽選名称" width="300"/>
            <el-table-column prop="parent_campaign.name" label="親キャンペーン名" width="450"/>
            <el-table-column prop="total" label="ナンバー数" width="140"/>
            <el-table-column
              fixed="right"
              label="操作"
              width="210">
              <template slot-scope="scope">
                <el-button  plain @click="editRow(scope.row)">編集</el-button>
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
import { mapGetters } from 'vuex'

export default {
  name: 'serial',
  components: {
    Pagination
  },
  computed: {
    ...mapGetters(['allowCreate'])
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
      Axios.get('/api/serials', { params:{page}}).then((res) => {
        this.pagination = Object.assign({},this.pagination,res.data)
        this.tableData = res.data.data
        this.loading = false
      })
    },
    handleCurrentChange (page) {
        this.$router.push({query: {page}})
        this.getList()
    },
    editRow (item) {
      this.$router.push('serials/' + item.id)
    }
  }
}
</script>

