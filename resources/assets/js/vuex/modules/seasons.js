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

    [types.SELECT_SEASON] (state, id) {
      state.currentID = id
    },

    [types.ADD_SEASON] (state, season) {
      addRecord(state.all, season, 'seasons')
    },

    [types.ADD_SEASONS] (state, seasons) {
      addRecords(state.all, seasons, 'seasons')
    }
  },
  actions: {
    getSeason (context, payload) {
      context.commit(types.SELECT_SEASON, payload.id)

      // GET /api/seasons/{id}
      axios.get(payload.url)
        .then(function (response) {
          // success callback
          let season = response.data
          context.commit(types.ADD_SEASON, season.data)
          context.commit(types.ADD_EPISODES, season.included)
          context.commit(types.ADD_SHOWS, season.included)
          context.commit(types.ADD_GENRES, season.included)
          context.commit(types.ADD_SEASON_VIEWS, season.included)
          context.commit(types.LOADED_ROUTE)
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    }
  }
}
