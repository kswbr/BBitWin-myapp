<template>
  <div id="headerBar">
    <div class="line"/>
    <el-menu
      :default-active="activeIndex2"
      class="el-menu-demo"
      mode="horizontal"
      @select="handleSelect"
      background-color="#545c64"
      text-color="#fff"
      active-text-color="#ffd04b">
      <el-menu-item index="1"><span style="font-size:18px;">BbmInstantWin</span></el-menu-item>
      <el-submenu index="2">
        <template slot="title">Campaigns</template>
        <el-menu-item index="2-1">item one</el-menu-item>
        <el-menu-item index="2-2">item two</el-menu-item>
        <el-menu-item index="2-3">item three</el-menu-item>
        <el-submenu index="2-4">
          <template slot="title">item four</template>
          <el-menu-item index="2-4-1">item one</el-menu-item>
          <el-menu-item index="2-4-2">item two</el-menu-item>
          <el-menu-item index="2-4-3">item three</el-menu-item>
        </el-submenu>
      </el-submenu>
      <el-menu-item class="right" index="100" ><a >USER NAME AREA</a></el-menu-item>
      <el-menu-item class="right" index="99" ><a id="logoutLink" @click="logout">Logout</a></el-menu-item>
    </el-menu>

  </div>
</template>

<script>
import * as types from '../../store/mutation-types.js'

export default {
  data () {
    return {
      activeIndex: '1',
      activeIndex2: '1'
    }
  },
  methods: {
    handleSelect (key, keyPath) {
      console.log(key, keyPath)
    },
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
