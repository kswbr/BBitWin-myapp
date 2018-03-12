<template>
  <div id="userArea">
    <el-container>
      <el-header>
        <h1>BbmInstantWin</h1>
      </el-header>
      <el-container>
        <el-aside width="200px">
          <SideBar />
        </el-aside>
        <el-container>
          <Campaign />
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

<style scoped>

  .el-header {
    margin-bottom: 15px;
    margin-left: 5px;
  }

  body > .el-container {
    margin-bottom: 40px;
  }

</style>
