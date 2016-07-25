import Vue from 'vue' 
import * as types from '../mutation-types'

// Movies
export const getMovies = (state, url, transition) => {
  state.dispatch(types.LOADING_MOVIES)
  Vue.http.get(url)
    .then(function (response) {
      // success callback
      const movies = response.data
      state.dispatch(types.ADD_MOVIES, movies.data)
      state.dispatch(types.ADD_MOVIE_LINKS, movies.links)
      state.dispatch(types.ADD_GENRES, movies.included)
      state.dispatch(types.LOADED_MOVIES)
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

export const selectMovie = (state, transition) => {
  state.dispatch(types.SELECT_MOVIE, transition.to.params.id)
}

export const getMovie = (state, transition) => {
  Vue.http.get('/api/movies/' + transition.to.params.id)
    .then(function (response) {
      // success callback
      const movie = response.data
      state.dispatch(types.ADD_MOVIE, movie.data)
      state.dispatch(types.ADD_GENRES, movie.included)
      state.dispatch(types.ADD_MOVIE_VIEWS, movie.included)
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

export const getMovieWithResults = (state, transition) => {
  Vue.http.get('/api/movies/' + transition.to.params.id + '?include=backdrops,posters,results')
    .then(function (response) {
      // success callback 
      const movie = response.data
      state.dispatch(types.ADD_MOVIE, movie.data)
      state.dispatch(types.ADD_IMAGES, movie.included)
      state.dispatch(types.ADD_MOVIE_RESULTS, movie.included)
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

export const patchMovie = (state, id, data, router) => {
  if(router) {
    state.dispatch(types.ADD_TOAST, {
      success: 'Updating Movie'
    })
  }
  
  Vue.http.patch('/api/movies/' + id, data)
    .then(function (response) {
      // success callback
      const movie = response.data
      state.dispatch(types.ADD_MOVIE, movie.data)
      if(router) {
        router.go('/movies/' + id)
      }
    }, function (response) {
      // error callback
      state.dispatch(types.ADD_TOAST, {
        error: 'Connection Error'
      })
    })
}