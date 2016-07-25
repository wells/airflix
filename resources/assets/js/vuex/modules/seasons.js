import { addRecord, addRecords } from '../helpers'
import * as types from '../mutation-types'

// initial state
const state = {
  currentID: null,
  all: [],
}

// mutations
const mutations = {
  [types.CLEAR_ALL] (state) {
    state.all = []
  },
  [types.SELECT_SEASON] (state, id) {
    state.currentID = id
  },
  [types.ADD_SEASON] (state, season) {
    addRecord(state.all, season, 'seasons')
  },
  [types.ADD_SEASONS] (state, seasons) {
    addRecords(state.all, seasons, 'seasons')
  }
}

export default {
  state,
  mutations
}
