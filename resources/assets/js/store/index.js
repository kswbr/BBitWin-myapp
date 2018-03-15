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
  debug,
  devenv
}

const mutations = {
}

const actions = {
}

const getters = {

}

const store = new Vuex.Store({
  actions,
  getters,
  state,
  strict: debug,
  plugins: devenv ? [createLogger()] : []
})

export default () => {
  return store
}
