<template>
  <el-container id="user" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item>ユーザー管理</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Users <small >ユーザー一覧 </small></h2>
          </el-col>
          <el-col :offset="20" :span="2">
            <el-button :disabled="!allowCreate" type="success" @click="() => (this.$router.push('users/create'))">
              新規作成
            </el-button>
          </el-col>
        </el-header >
      </el-row>
      <Pagination :handleCurrentChange="handleCurrentChange" :pagination="pagination" />

      <el-row>
        <el-col :offset="2" :span="20">
          <el-table  v-loading="loading"  :data="tableData" >
            <el-table-column prop="name" label="ユーザー名" />
            <el-table-column prop="email" label="メールアドレス" />
            <el-table-column
              fixed="right"
              label="操作"
              width="140">
              <template slot-scope="scope">
                <el-button plain @click="editRow(scope.row)" >編集</el-button>
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
  name: 'User',
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
      Axios.get('/api/users', { params:{page}}).then((res) => {
        this.pagination = Object.assign({},this.pagination,res.data)
        this.tableData = res.data.data
        this.loading = false
        console.log(this.tableData)
      })
    },
    handleCurrentChange (page) {
        this.$router.push({query: {page}})
        this.getList()
    },
    editRow (item) {
      this.$router.push('users/' + item.id)
    }
  }
}
</script>

