import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/ja'
import VueRouter from 'vue-router'
import router from './router'
import getStore from './store'
import * as types from './store/mutation-types.js'

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

const store = getStore()

const bootstrap = require('./bootstrap');

window.Vue = require('vue');

Vue.use(ElementUI, { locale })
Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('app', require('./components/App.vue'));


const app = new Vue({
    el: '#app',
    store,
    router
});

store.subscribe((mutation, state) => {
    if (mutation.type === types.LOGGED_IN) {
        bootstrap.fetchToken()
        router.push("/admin/userarea")
    }

    if (mutation.type === types.LOGGED_OUT) {
        bootstrap.removeToken()
        router.push("/admin/")
    }

})

console.log(bootstrap)
bootstrap.fetchToken()
