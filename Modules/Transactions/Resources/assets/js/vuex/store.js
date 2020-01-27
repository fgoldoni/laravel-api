import Vue from 'vue'
import Vuex from 'vuex'
import * as actions from './actions'
import * as getters from './getters'
import transactions from '../modules/admin/transactions/vuex/store'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

const state = {
  currentUser: [],
  languages: []
}

const mutations = {
  LOGIN_USER (state, user) {
    state.currentUser = user
  },
  LOAD_LANGUAGES (state, languages) {
    state.languages = languages
  }
}

export default new Vuex.Store({
  getters,
  actions,
  state,
  mutations,
  modules: {
    transactions
  },
  strict: debug
})
