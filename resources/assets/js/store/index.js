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
  },
  [types.API_REQUEST_FAILED] (state, name, error) {
    state.inRequest = false
    state.currentRequest = name
    state.requestHistory.push(name)
    state.error = error
    console.error(error)
  },

  [types.LOGGED_IN] (state) {
    state.loggedIn = true
  },
  [types.LOGGED_OUT] (state) {
    state.loggedIn = false
  }
}

const actions = {
  requestStart ({dispatch, commit, state, rootGetters}, payload) {
    return new Promise((resolve, reject) => {
      if (payload.checkDuplication && state.inRequest) {
        return reject(new Error('inRequest...'))
      }
      commit(types.API_REQUEST_START, payload.label)

      return resolve()
    })
  }
}

const getters = {
  checkRequest: state => () => state.inRequest
}

const store = new Vuex.Store({
  actions,
  getters,
  state,
  types,
  mutations,
  strict: debug,
  plugins: devenv ? [createLogger()] : []
})

export default () => {
  return store
}
