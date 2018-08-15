<template>
  <el-container id="dashboard" >
    <el-main>
      <el-row>
        <el-breadcrumb separator="/">
          <el-breadcrumb-item >Home</el-breadcrumb-item>
        </el-breadcrumb>
      </el-row>
      <el-row>
        <el-header >
          <el-col :offset="1" :span="21">
          <h2 class="h2">BB It Win<small >ホーム </small></h2>
          </el-col>
        </el-header >
      </el-row>
      <el-row>
        <el-col :offset="3" :span="8">
          <el-card class="box-card" >
            <el-button style="font-size:1.25em" @click="$router.push('userarea/campaigns')" type="text" :disabled="!user.allow_campaign">
              <i class="el-icon-edit-outline"></i> キャンペーン管理
            </el-button>
          </el-card>
        </el-col>
        <el-col :offset="1" :span="8">
          <el-card class="box-card" >
            <el-button style="font-size:1.25em" @click="$router.push('userarea/votes')" type="text" :disabled="!user.allow_vote">
              <i class="el-icon-document"></i> 投票管理
            </el-button>
          </el-card>
        </el-col>
      </el-row>
      <el-row>
        <el-col :offset="3" :span="8">
          <el-card class="box-card" >
            <el-button style="font-size:1.25em" @click="$router.push('userarea/users')" type="text" :disabled="!user.allow_user">
              <i class="el-icon-check"></i> ユーザー管理
            </el-button>
          </el-card>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
import { mapState } from 'vuex'
import * as types from '../../store/mutation-types.js'
import Axios from 'axios'
import _ from 'lodash'

export default {
  name: 'Dashboard',
  computed: {
    ...mapState(['user'])
  },
  mounted () {
  },
  methods: {
  }
}
</script>

</script>

<style scoped>
</style>
