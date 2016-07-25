import * as types from '../mutation-types'

// initial state
const state = {
  showMenu: false,
  showSearch: false,
}

// mutations
const mutations = {
  [types.HIDE_MENU] (state) {
    state.showMenu = false
  },
  [types.TOGGLE_MENU] (state) {
    state.showMenu = ! state.showMenu
  },
  [types.TOGGLE_SEARCH] (state) {
    state.showSearch = ! state.showSearch
  }
}

export default {
  state,
  mutations
}
