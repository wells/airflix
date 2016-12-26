import { addRecords } from '../helpers'
import * as types from '../mutation-types'

export default {
  state: {
    all: [],
  },
  mutations: {
    [types.CLEAR_ALL] (state) {
      state.all = []
    },
    [types.ADD_IMAGES] (state, images) {
      addRecords(state.all, images, 'images')
    }
  }
}
