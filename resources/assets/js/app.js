import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/ja'
import VueRouter from 'vue-router'
import router from './router'
import Axios from 'axios'
import getStore from './store'
import * as types from './store/mutation-types.js'

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


const bootstrap = require('./bootstrap');

/**
 *
 * Vuex依存の初期化処理開始
 *
 * */
const store = getStore()


// ログイン済みのセッションがあるか判定
if (bootstrap.fetchToken()) {
   store.commit(types.ALREADY_LOGGED_IN)
}

// ルーターの変更をstateで参照可能にする
router.beforeEach((to, from, next) => {
    store.commit(types.CHANGE_ROUTE, {to,from})
    next()
})

// APIコール時のローディング表示
Axios.interceptors.request.use((config) => {
  store.commit(types.API_REQUEST_START,{label: 'REQUEST_START',config})
  return config
},(error) => {
  // Do something with request error
  return Promise.reject(error);
});

// API終了時のローディング非憑依
Axios.interceptors.response.use((response) => {
    store.commit(types.API_REQUEST_END,{label: 'REQUEST_END', response})
    return response;
},  (error) => {
    // Do something with response error
    // トークンが切れたりして401,500のときに ログインへ戻す
    if (error.response.status === 401) {
        store.commit(types.API_REQUEST_FAILED,{label: '401', error})
    }

    if (error.response.status === 500) {
        store.commit(types.API_REQUEST_FAILED,{label: '500', error})
    }

    return Promise.reject(error.response);
})

store.subscribe((mutation, state) => {

    // ログイン時処理
    if (mutation.type === types.LOGGED_IN) {
        bootstrap.fetchToken()
        if (state.route.from && String(state.route.from).indexOf("/admin/userarea") === 0) {
            router.push(state.route.from)
        } else {
            router.push("/admin/userarea")
        }
    }

    if (mutation.type === types.LOGGED_OUT) {
        bootstrap.removeToken()
        router.push("/admin/")
        location.reload()
    }

    if (mutation.type === types.API_REQUEST_FAILED && !store.state.loggedIn) {
        router.push('/admin/')
    }
})

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




