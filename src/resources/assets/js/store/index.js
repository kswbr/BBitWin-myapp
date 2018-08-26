import Vue from 'vue'
import Vuex from 'vuex'
import createLogger from 'vuex/dist/logger'

import { Message } from 'element-ui'
import * as types from './mutation-types'

const debug = process.env.NODE_ENV !== 'production'
const devenv = process.env.NODE_ENV === 'development'

Vue.use(Vuex)

const state = {
  loggedIn: false,
  inRequest: false,
  route: {},
  requestConfig: {},
  requestResponse: {},
  user: {},
  errors: {},
  success: {},
  debug,
  devenv
}

const mutations = {
  [types.API_REQUEST_START] (state, payload) {
    state.inRequest = true
    state.requestConfig = Object.assign({}, payload.config)
  },
  [types.API_REQUEST_END] (state, payload) {
    state.inRequest = false
    state.requestResponse = Object.assign({}, payload.response)
    state.errors = {}
  },
  [types.API_REQUEST_FAILED] (state, payload) {
    state.inRequest = false
    state.error = payload.error
  },

  [types.LOGGED_IN] (state, auth) {
    localStorage.setItem('Authorization.access_token', auth.access_token)
    localStorage.setItem('Authorization.refresh_token', auth.refresh_token)
    state.loggedIn = true
  },
  [types.ALREADY_LOGGED_IN] (state) {
    state.loggedIn = true
  },
  [types.LOGGED_OUT] (state) {
    state.loggedIn = false
  },
  [types.CHANGE_ROUTE] (state, payload) {
    Message.closeAll()
    state.route = Object.assign({}, payload)
  },

  [types.FETCH_USER] (state, payload) {
    state.user = Object.assign({}, payload)
  },

  [types.FORM_VALIDATION_FAILED] (state, payload) {
    Message.closeAll()
    state.inRequest = false
    state.errors = Object.assign({}, payload)
  },

  [types.FORM_VALIDATION_SUCCESS] (state, payload) {
    Message.closeAll()
    state.inRequest = false
    state.success = Object.assign({}, payload)
  }

}

const actions = {
  requestStart ({ dispatch, commit, state, rootGetters }, payload) {
    return new Promise((resolve, reject) => {
      if (payload.checkDuplication && state.inRequest) {
        return reject(new Error('inRequest...'))
      }
      commit(types.API_REQUEST_START, payload)

      return resolve()
    })
  }
}

const getters = {
  checkRequest: state => () => state.inRequest,
  allowCreate: state => state.user.role >= 1,
  allowDelete: state => state.user.role >= 1
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
