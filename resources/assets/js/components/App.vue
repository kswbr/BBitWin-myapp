<template>
  <div class="container">
    <Login
      v-if="!loggedIn"
      :login-app="login" />

    <UserArea v-if="loggedIn"/>
  </div>
</template>

<script>

import Login from './Login.vue'
import UserArea from './UserArea.vue'
import Axios from 'axios'

export default {
  components: {
    Login,
    UserArea
  },
  data () {
    return {
      loggedIn: true
    }
  },
  mounted () {
    console.log('Component mounted!!.')
    Axios.get('/api/user').then((res) => {
      console.log(res)
    })
  },
  methods: {
    login () {
      this.loggedIn = true
      console.log('loggedIn', this.loggedIn)
    }
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
