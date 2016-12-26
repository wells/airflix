import { addRecords } from '../helpers'
import * as types from '../mutation-types'

export default {
  state: {
    all: []
  },
  mutations: {
    [types.CLEAR_ALL] (state) {
      state.all = []
    },
    [types.ADD_GENRES] (state, genres) {
      addRecords(state.all, genres, 'genres')
    }
  }
}
