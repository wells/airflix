import { addRecord, addRecords } from '../helpers'
import * as types from '../mutation-types'

// initial state
const state = {
  all: []
}

// mutations
const mutations = {
  [types.CLEAR_ALL] (state) {
    state.all = []
  },
  [types.ADD_GENRES] (state, genres) {
    addRecords(state.all, genres, 'genres')
  }
}

export default {
  state,
  mutations
}
