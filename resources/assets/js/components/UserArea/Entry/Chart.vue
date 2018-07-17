<template>
  <el-container id="entryChart" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item :to="{path:'/admin/userarea'}">Home</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../../../'}">キャンペーン</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'../'}" >抽選賞品</el-breadcrumb-item>
          <el-breadcrumb-item :to="{path:'.'}">応募状況</el-breadcrumb-item>
          <el-breadcrumb-item>グラフ表示</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
         <el-col :offset="1" :span="21">
            <h2 class="h2">Entry Chart <small >グラフ表示 </small></h2>
          </el-col>
        </el-header >
      </el-row>
      <el-row>
        <el-col :offset="1" :span="21">
          <canvas id="canvas"></canvas>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>

import Axios from 'axios'
import _ from 'lodash'
import Chart from 'Chart.js'

export default {
  name: 'EntryChart',
  data () {
    return {
        datasets: {
            all: null
        },
        configBase: {
            type: 'line',
            options: {
                responsive: true,
                title:{
                    display:false,
                    text:'Chart.js Line Chart'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Date-Hour'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Count'
                        }
                    }]
                }
            }
        }
    }
  },
  mounted () {
    this.fetch()
  },
  methods: {
    fetch () {
      Axios.get('/api/campaigns/' + this.$route.params.campaignId + '/lotteries/' + this.$route.params.lotteryId + '/entries/chart').then((res) => {
        this.datasets.all = Object.assign({}, this.datasets.all, res.data)
        this.createDatasets()
      }).catch((e) => (console.error(e)))
    },
    createDatasets () {

        const filterByState = (states,dataset) => {
            let copyDataset = _.assign({}, dataset)
            _.each(copyDataset, (v, k) => {
                copyDataset[k] = _.filter(v, (nv) => {
                    return _.indexOf(states, nv.state) >= 0
                })
            })
            return copyDataset
        }

        const datasetListTotal = [
            this.createDataset(this.datasets.all,"応募合計数推移","#ff6384"),
            this.createDataset(filterByState([2,3],this.datasets.all) ,"当選者推移","#36a2eb")
        ]

        const createDateLabel = (dataset) =>{
            const labels = _.keys(dataset);
            return _.map(labels,(label) => {
                const list = label.split("-");
                return list[0]  + "/" + list[1] + "/" + list[2] + " " + list[3] + ":00";
            })
        }


        const configTotal = _.assign({},this.configBase,{
            data: {
                labels: createDateLabel(this.datasets.all),
                datasets: datasetListTotal,
            },
            options:{
                title:{
                    display:true,
                    text:'応募者全体推移'
                }
            }
        })
        const ctx = document.getElementById("canvas").getContext("2d");
        window.myLineTotal = new Chart(ctx, configTotal);
    },
    createDataset (dataset,label,color) {

       const dataList = _.reduce(dataset,(result,data) => {
           const before = _.last(result) ? _.last(result) : 0;
           const sum = before + data.length;
           result.push(sum)
           return result;
       },[]);

       const pointList = _.map(dataset, (data) => {
           return data.length == 0 ? 0 : 1;
       });

       return {
           label: label,
           backgroundColor: color,
           borderColor: color,
           data:dataList,
           fill: false,
           pointRadius: pointList,
           borderWidth:2,
           pointHitRadius:5
       }
    }
  }
}
</script>

<style scoped>
canvas{
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
}

</style>
