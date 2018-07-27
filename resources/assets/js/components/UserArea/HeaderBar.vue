<template>
  <div id="headerBar">
    <div class="line"/>
    <el-menu
      class="el-menu-demo"
      mode="horizontal"
      background-color="#545c64"
      text-color="#fff"
      active-text-color="#ffd04b">
      <el-menu-item index="1"><span style="font-size:18px;">BbmInstantWin</span></el-menu-item>
      <el-menu-item class="right" index="100" >{{user.name}}</el-menu-item>
      <el-menu-item class="right" index="99" ><a id="logoutLink" @click="logout">Logout</a></el-menu-item>
    </el-menu>
  </div>
</template>

<script>
import * as types from '../../store/mutation-types.js'
import Axios from 'axios'
import _ from 'lodash'

export default {
  data () {
    return {
      user: {}
    }
  },
  mounted () {
    if (!_.isEmpty(this.user)) { return }
    Axios.get('/api/user/info').then((res) => {
      this.user = Object.assign({},res.data)
    })
  },
  methods: {
    logout () {
      if (window.confirm('ログアウトしますか？')) {
        this.$store.commit(types.LOGGED_OUT)
      }
    }
  }
}
</script>
<style scoped>
.el-menu-item.right {
    float:right;
}
</style>
