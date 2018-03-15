import Vue from 'vue'
import Vuex from 'vuex'
import createLogger from 'vuex/dist/logger'

import * as types from './mutation-types'

const debug = process.env.NODE_ENV !== 'production'
const devenv = process.env.NODE_ENV === 'development'

Vue.use(Vuex)

const state = {
  loggedIn: false,
  inRequest: false,
  currentRequest: '',
  requestHistory: [],
  debug,
  devenv
}

const mutations = {
  [types.API_REQUEST_START] (state, name = '') {
    state.inRequest = true
    state.currentRequest = name
    state.requestHistory.push(name)
  },
  [types.API_REQUEST_END] (state, name = '') {
    state.inRequest = false
    state.currentRequest = name
    state.requestHistory.push(name)
  }
}

const actions = {
}

const getters = {
  checkRequest: state => () => state.inRequest
}

const store = new Vuex.Store({
  actions,
  getters,
  state,
  mutations,
  strict: debug,
  plugins: devenv ? [createLogger()] : []
})

export default () => {
  return store
}
