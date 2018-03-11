<template>
<div id="userArea">
<el-container>
  <el-header>
        <h1>BbmInstantWin</h1>
  </el-header>
  <el-container>
        <el-aside width="200px">
            <SideBar></SideBar>
        </el-aside>
        <el-container>
            <Campaign></Campaign>
        </el-container>
  </el-container>
</el-container>
</div>
</template>

<script>

import Campaign from './UserArea/Campaign.vue'
import SideBar from './UserArea/SideBar.vue'

export default {
    name: 'UserArea',
    components: {
        Campaign,
        SideBar
    }
}
</script>

<style>

  body > .el-container {
    margin-bottom: 40px;
  }

  .el-container:nth-child(5) .el-aside,
  .el-container:nth-child(6) .el-aside {
    line-height: 260px;
  }

  .el-container:nth-child(7) .el-aside {
    line-height: 320px;
  }
</style>
