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
          <transition
            mode="out-in"
            enter-active-class="fadeIn"
            leave-active-class="fadeOut"
            appear>
            <router-view class="animated"/>
          </transition>
        </el-container>
      </el-container>
    </el-container>
  </div>
</template>

<script>

import SideBar from './UserArea/SideBar.vue'

export default {
  name: 'UserArea',
  components: {
    SideBar
  },
  mounted () {
    console.log('UserArea')
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
