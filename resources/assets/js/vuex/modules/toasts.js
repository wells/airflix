import * as types from '../mutation-types'

// initial state
const state = {
  current: null,
}

// mutations
const mutations = {
  [types.ADD_TOAST] (state, toast) {
    state.current = toast
  }
}

export default {
  state,
  mutations
}