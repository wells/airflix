import * as types from '../mutation-types'

// initial state
const state = {
  current: null,
}

// mutations
const mutations = {
  [types.ADD_SETTINGS] (state, settings) {
    state.current = settings
  },
  [types.SET_SETTINGS_ATTRIBUTES] (state, data) {
    for (var name in data) {
      state.current.attributes[name] = data[name]
    }
  }
}

export default {
  state,
  mutations
}