<template>
  <el-container id="lottery" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../.'}">キャンペーン</el-breadcrumb-item>
          <el-breadcrumb-item>抽選賞品</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Lotteries <small >抽選賞品一覧 </small></h2>
          </el-col>
          <el-col :offset="20" :span="2">
            <el-button :disabled="!allowCreate" type="success" @click="() => (this.$router.push('lotteries/create'))">
              新規作成
            </el-button>
          </el-col>
        </el-header >
      </el-row>
      <Pagination :handleCurrentChange="handleCurrentChange" :pagination="pagination" />

      <el-row>
        <el-col :offset="2" :span="20">
          <el-table  v-loading="loading"  :data="tableData" >
            <el-table-column prop="code" label="コード" width="140"/>
            <el-table-column prop="name" label="賞品名称" width="280"/>
            <el-table-column prop="rate" label="確率(%)"/>
            <el-table-column prop="entries_count" label="応募数"/>
            <el-table-column prop="entries_win_completed_count" label="当選数 (応募完了)"/>
            <el-table-column prop="state.label" label="状態" width="100"/>
            <el-table-column
              fixed="right"
              label="操作"
              width="210">
              <template slot-scope="scope">
                <el-button plain @click="editRow(scope.row)" >編集</el-button>
                <el-button plain @click="showEntry(scope.row)">応募<i class="el-icon-arrow-right"></i></el-button>
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
  name: 'Lottery',
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
      Axios.get('/api/campaigns/' + this.$route.params.campaignId + '/lotteries', { params:{page}}).then((res) => {
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
    showEntry (item) {
      this.$router.push('lotteries/' + item.id + '/entries')
    },
    editRow (item) {
      this.$router.push('lotteries/' + item.id)
    }
  }
}
</script>

