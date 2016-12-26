import { addRecords } from '../helpers'
import * as types from '../mutation-types'

export default {
  state: {
    movies: [],
    shows: [],
  },
  mutations: {
    [types.CLEAR_ALL] (state) {
      state.movies = []
      state.shows = []
    },
    [types.ADD_MOVIE_RESULTS] (state, results) {
      addRecords(state.movies, results, 'movie-results')
    },
    [types.ADD_SHOW_RESULTS] (state, results) {
      addRecords(state.shows, results, 'show-results')
    }
  }
}
