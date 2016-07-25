import { addRecord, addRecords } from '../helpers'
import * as types from '../mutation-types'

// initial state
const state = {
  all: [],
}

// mutations
const mutations = {
  [types.CLEAR_ALL] (state) {
    state.all = []
  },
  [types.ADD_IMAGES] (state, images) {
    addRecords(state.all, images, 'images')
  }
}

export default {
  state,
  mutations
}
