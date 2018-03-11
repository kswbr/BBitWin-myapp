<template>
<div id="login">
   <el-row >
        <h1>BbmInstantWin</h1>
        <h2>Login</h2>
        <el-col :span="12" :offset="5">
        <el-form :model="form" status-icon  label-width="120px" >
             <el-form-item  >
                <el-input placeholder="ユーザー名又はメールアドレス" v-model="form.username"></el-input>
             </el-form-item>
             <el-form-item  >
                <el-input type="password" v-model="form.password" auto-complete="off"></el-input>
             </el-form-item>
             <el-form-item >
                <el-button type="primary" @click="submitForm('form')">Login</el-button>
             </el-form-item>
        </el-form>
        </el-col>
   </el-row>
</div>
</template>

<script>

export default {
    name: 'login',
    props: {
        loginApp: Function
    },
    mounted: function () {
        console.log('mouted')
    },
    data () {
        return {
            form: {
                username: '',
                password: ''
            }
        }
    },
    methods: {
        submitForm (name) {
            if (this.form.username && this.form.password) {
                this.loginApp()
            }
        }
    }
}
</script>

<style>
#login {
  margin-top: 60px;
  text-align: center;
}

</style>
