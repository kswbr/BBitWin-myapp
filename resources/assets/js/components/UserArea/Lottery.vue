<template>
  <el-container id="lottery" >
    <el-main>
      <el-row>
        <el-header >
          <el-breadcrumb separator="/">
            <el-breadcrumb-item :to="{path:'../../.'}">キャンペーン</el-breadcrumb-item>
            <el-breadcrumb-item>抽選賞品</el-breadcrumb-item>
          </el-breadcrumb>
          <el-col :offset="1" :span="21">
            <h2 class="h2">Lotteries <small >抽選賞品一覧 </small></h2>
          </el-col>
          <el-col :offset="20" :span="2">
            <el-button type="success" @click="() => (this.$router.push('lotteries/create'))">
              新規作成
            </el-button>
          </el-col>
        </el-header >
      </el-row>

      <el-row>
        <el-col :offset="2" :span="20">
          <el-table  v-loading="loading"  :data="tableData" >
            <el-table-column prop="code" label="コード" width="140"/>
            <el-table-column prop="name" label="賞品名称" width="400"/>
            <el-table-column prop="total" label="賞品総数"/>
            <el-table-column prop="limit" label="当選制限数"/>
            <el-table-column prop="start_date" label="応募開始　日時" width="100"/>
            <el-table-column prop="end_date" label="応募終了　日時" width="100"/>
            <el-table-column
              fixed="right"
              label="操作"
              width="130">
              <template slot-scope="scope">
                <el-button type="text" @click="editRow(scope.row)" >編集</el-button>
                <el-button type="text" @click="showEntry(scope.row)">応募状況</el-button>
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
  name: 'Lottery',
  data () {
    return {
      tableData: [],
      loading: true
    }
  },
  mounted () {
    this.getList()
  },
  methods: {
    getList () {
      Axios.get('/api/campaigns/' + this.$route.params.campaignId + '/lotteries', { page: 0 }).then((res) => {
        this.tableData = res.data.data
        this.loading = false
        console.log(this.tableData)
      })
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

