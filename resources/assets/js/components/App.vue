<template>
    <div class="container">
        <Login v-if="!logged_in" :loginApp="login"></Login>
        <UserArea v-if="logged_in"></UserArea>
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
            logged_in: true
        }
    },
    mounted() {
        console.log('Component mounted!!.')
        Axios.get('/api/user').then((res) =>  {
            console.log(res)
        })
    },
    methods: {
        login () {
            this.logged_in = true
            console.log('logged_in', this.logged_in)
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
