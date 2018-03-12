<template>
  <div class="container">
    <router-view />
  </div>
</template>

<script>

import Axios from 'axios'

export default {
  mounted () {
    console.log('Component mounted!!.')
    Axios.get('/api/user').then((res) => {
      console.log(res)
    })
  }
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
