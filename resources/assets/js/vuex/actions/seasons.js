import Vue from 'vue' 
import * as types from '../mutation-types'

// Seasons
export const selectSeason = (state, transition) => {
  state.dispatch(types.SELECT_SEASON, transition.to.params.id)
}

export const getSeason = (state, transition) => {
  Vue.http.get('/api/seasons/' + transition.to.params.id)
    .then(function (response) {
      // success callback
      const season = response.data
      state.dispatch(types.ADD_SEASON, season.data)
      state.dispatch(types.ADD_EPISODES, season.included)
      state.dispatch(types.ADD_SHOWS, season.included)
      state.dispatch(types.ADD_GENRES, season.included)
      state.dispatch(types.ADD_SEASON_VIEWS, season.included)
      if (transition) {
        transition.next()
      }
    }, function (response) {
      // error callback
      state.dispatch(types.ADD_TOAST, {
        error: 'Connection Error'
      })
      if(transition && response.status != 0) {
        transition.abort()
      }
    })
}