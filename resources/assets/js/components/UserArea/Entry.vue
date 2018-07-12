<template>
  <el-container id="entry" >
    <el-main>
      <el-row>
        <el-header >
          <el-breadcrumb separator="/">
            <el-breadcrumb-item :to="{path:'../../../.'}">キャンペーン</el-breadcrumb-item>
            <el-breadcrumb-item :to="{path:'../.'}" >抽選賞品</el-breadcrumb-item>
            <el-breadcrumb-item>応募状況</el-breadcrumb-item>
          </el-breadcrumb>
          <el-col :offset="1" :span="21">
            <h2 class="h2">Entries <small >応募状況一覧 </small></h2>
          </el-col>
        </el-header >
      </el-row>

      <el-row>
        <el-col :offset="2" :span="20">
          <el-table v-loading="loading" :data="tableData" >
            <el-table-column prop="id" label="お問い合わせ番号"/>
            <el-table-column prop="player_id" label="ユーザー番号"/>
            <el-table-column prop="state_label" label="状態"/>
            <el-table-column prop="created_at" label="応募日時"/>
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
  name: 'Entry',
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
      Axios.get('/api/campaigns/' + this.$route.params.campaignId + '/lotteries/' + this.$route.params.lotteryId + '/entries', { page: 0 }).then((res) => {
        this.tableData = res.data.data
        this.loading = false
      })
    },
    editRow (item) {
      this.$router.push('entries/' + item.id)
    }
  }
}
</script>

