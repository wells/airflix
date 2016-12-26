import { addRecords } from '../helpers'
import * as types from '../mutation-types'

export default {
  state: {
    movies: [],
    seasons: [],
    shows: [],
  },
  mutations: {
    [types.CLEAR_ALL] (state) {
      state.movies = []
      state.seasons = []
      state.shows = []
    },

    [types.ADD_MOVIE_VIEWS] (state, views) {
      addRecords(state.movies, views, 'movie-views')
    },

    [types.ADD_SEASON_VIEWS] (state, views) {
      addRecords(state.seasons, views, 'season-views')
    },
    
    [types.ADD_SHOW_VIEWS] (state, views) {
      addRecords(state.shows, views, 'show-views')
    }
  }
}
