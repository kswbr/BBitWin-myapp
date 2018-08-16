<template>
  <el-container id="entry" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../../../.'}">キャンペーン</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../.'}" >抽選賞品</el-breadcrumb-item>
          <el-breadcrumb-item>応募状況</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Entries <small >応募状況一覧 </small></h2>
          </el-col>
          <el-col :offset="17" :span="2">
            <InfoModal :campaignId="this.$route.params.campaignId" :lotteryId="this.$route.params.lotteryId"/>
          </el-col>
          <el-col :offset="1" :span="1">
            <el-button type="primary" @click="() => (this.$router.push('entries/chart'))">
              グラフ表示
            </el-button>
          </el-col>
        </el-header >
      </el-row>
      <Pagination :handleCurrentChange="handleCurrentChange" :pagination="pagination" />
      <el-row>
        <el-col :offset="2" :span="20">
          <el-table v-loading="loading" :data="tableData" :row-class-name="tableRowClassName">
            <el-table-column prop="id" label="お問い合わせ番号"/>
            <el-table-column prop="player_id" label="ユーザー番号"/>
            <el-table-column prop="state_data.label" label="状態"/>
            <el-table-column prop="created_at" label="応募日時"/>
            <el-table-column
              fixed="right"
              label="操作"
              width="130">
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

<style >
  .el-table .warning-row {
    background: oldlace;
  }

  .el-table .success-row {
    background: #f0f9eb;
  }
</style>

<script>
import Axios from 'axios'
import Pagination from './Pagination'
import InfoModal from './Lottery/InfoModal.vue'

export default {
  name: 'Entry',
  components: {
    Pagination,
    InfoModal
  },
  data () {
    return {
      stateList: {},
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
      return Axios.get(this.apiPath, {params:{page}}).then((res) => {
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
      this.$router.push('entries/' + item.id)
    },
    tableRowClassName({row, rowIndex}) {
      return row.state_data.css_style + '-row'
    }
  },
  computed: {
    apiPath: function () {
        return '/api/campaigns/' + this.$route.params.campaignId + '/lotteries/' + this.$route.params.lotteryId + '/entries'
    }
  }
}
</script>

