import { addRecord, addRecords } from '../helpers'
import * as types from '../mutation-types'

export default {
  state: {
    currentID: null,
    all: [],
  },
  mutations: {
    [types.CLEAR_ALL] (state) {
      state.all = []
    },
    [types.SELECT_EPISODE] (state, id) {
      state.currentID = id
    },
    [types.ADD_EPISODE] (state, episode) {
      addRecord(state.all, episode, 'episodes')
    },
    [types.ADD_EPISODES] (state, episodes) {
      addRecords(state.all, episodes, 'episodes')
    }
  }
}
