import * as types from '../mutation-types'

export default {
  state: {
    current: null,
  },
  mutations: {
    [types.ADD_TOAST] (state, toast) {
      state.current = toast
    }
  }
}