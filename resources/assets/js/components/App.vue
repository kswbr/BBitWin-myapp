<template>
  <div class="container"  v-loading.fullscreen.lock="inRequest">
    <router-view />
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  mounted () {
  },
  computed: {
    ...mapState(["inRequest"])
  },
}
</script>

<style>
body {
    margin:0;
}
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}

.el-button {
  margin-top: 10px !important;
  &:last-child {
    margin-bottom: 0;
  }
}

</style>
