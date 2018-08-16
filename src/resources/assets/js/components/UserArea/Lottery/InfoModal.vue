<template>
<span>
  <el-button type="warning" @click="dialogTableVisible = true">応募状況表示</el-button>
  <el-dialog title="応募状況" :visible.sync="dialogTableVisible">
    <el-row>
      <el-col :offset="0" :span="7">
        <el-card class="box-card" >
          <el-row style="margin-bottom:0">
            <el-col>
              <strong type="primary">応募数</strong>
            </el-col>
            <el-col style="text-align:right;font-size:2em;color:#409EFF;">{{lottery.entries_count}}</el-col>
          </el-row>
        </el-card>
      </el-col>
      <el-col :offset="1" :span="7">
        <el-card class="box-card" >
          <el-row style="margin-bottom:0">
            <el-col>
              <strong type="primary">当選数(応募未完了)</strong>
            </el-col>
            <el-col style="text-align:right;font-size:2em;color:#409EFF;">{{lottery.entries_win_count}}</el-col>
          </el-row>
        </el-card>
      </el-col>
      <el-col :offset="1" :span="7">
        <el-card class="box-card" >
          <el-row style="margin-bottom:0">
            <el-col>
              <strong type="primary">当選数(応募完了)</strong>
            </el-col>
            <el-col style="text-align:right;font-size:2em;color:#409EFF;">{{lottery.entries_win_completed_count}}</el-col>
          </el-row>
        </el-card>
      </el-col>
    </el-row>
    <el-row>
     <el-col :offset="0" :span="7">
        <el-card class="box-card" >
          <el-row style="margin-bottom:0">
            <el-col>
              <strong type="primary">当選制限数</strong>
            </el-col>
            <el-col style="text-align:right;font-size:2em;color:#409EFF;">{{lottery.limit}}</el-col>
          </el-row>
        </el-card>
      </el-col>
      <el-col :offset="1" :span="7">
        <el-card class="box-card" >
          <el-row style="margin-bottom:0">
            <el-col>
              <strong type="primary">全体賞品数</strong>
            </el-col>
            <el-col style="text-align:right;font-size:2em;color:#409EFF;">{{lottery.total}}</el-col>
          </el-row>
        </el-card>
      </el-col>
      <el-col :offset="1" :span="7">
        <el-card class="box-card" >
          <el-row style="margin-bottom:0">
            <el-col>
              <strong type="primary">当選確率</strong>
            </el-col>
            <el-col style="text-align:right;font-size:2em;color:#409EFF;">{{lottery.rate}}%</el-col>
          </el-row>
        </el-card>
      </el-col>
    </el-row>
    <el-row>
      <el-col :offset="0" :span="7">
        <el-card class="box-card" >
          <el-row style="margin-bottom:0">
            <el-col>
            <strong type="primary">残り有効賞品数<br>(当選制限数 - 応募数)</strong>
            </el-col>
            <el-col style="text-align:right;font-size:2em;color:#409EFF;">{{lottery.remaining}}</el-col>
          </el-row>
        </el-card>
      </el-col>
      <el-col :offset="1" :span="7">
        <el-card class="box-card" >
          <el-row style="margin-bottom:0">
            <el-col>
              <strong type="primary">残り全体賞品数<br>(全体賞品数 - 応募数)</strong>
            </el-col>
            <el-col style="text-align:right;font-size:2em;color:#409EFF;">{{lottery.total - lottery.entries_win_count - lottery.entries_win_completed_count}}</el-col>
          </el-row>
        </el-card>
      </el-col>
    </el-row>
  </el-dialog>
</span>
</template>

<script>
import Axios from 'axios'
export default {
  props:{
    campaignId:Number,
    lotteryId:Number
  },
  data() {
    return {
      lottery:{},
      dialogTableVisible: false,
    };
  },
  mounted () {
    this.fetch()
  },
  methods: {
    fetch () {
      Axios.get('/api/campaigns/' + this.campaignId + '/lotteries/' + this.lotteryId).then((res) => {
        console.log(res.data)
        this.lottery = Object.assign({}, this.lottery, res.data)
      }).catch((e) => (console.error(e)))
    }
  }
};
</script>
