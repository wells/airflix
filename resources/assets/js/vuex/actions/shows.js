import Vue from 'vue' 
import * as types from '../mutation-types'

// Shows
export const getShows = (state, url, transition) => {
  state.dispatch(types.LOADING_SHOWS)
  Vue.http.get(url)
    .then(function (response) {
      // success callback
      const shows = response.data
      state.dispatch(types.ADD_SHOWS, shows.data)
      state.dispatch(types.ADD_SHOW_LINKS, shows.links)
      state.dispatch(types.ADD_GENRES, shows.included)
      state.dispatch(types.LOADED_SHOWS)
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

export const selectShow = (state, transition) => {
  state.dispatch(types.SELECT_SHOW, transition.to.params.id)
}

export const getShow = (state, transition) => {
  Vue.http.get('/api/shows/' + transition.to.params.id)
    .then(function (response) {
      // success callback
      const show = response.data
      state.dispatch(types.ADD_SHOW, show.data)
      state.dispatch(types.ADD_GENRES, show.included)
      state.dispatch(types.ADD_SEASONS, show.included)
      state.dispatch(types.ADD_SHOW_VIEWS, show.included)
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

export const getShowWithResults = (state, transition) => {
  Vue.http.get('/api/shows/' + transition.to.params.id + '?include=backdrops,posters,results')
    .then(function (response) {
      // success callback
      const show = response.data
      state.dispatch(types.ADD_SHOW, show.data)
      state.dispatch(types.ADD_GENRES, show.included)
      state.dispatch(types.ADD_SEASONS, show.included)
      state.dispatch(types.ADD_IMAGES, show.included)
      state.dispatch(types.ADD_SHOW_RESULTS, show.included)
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

export const patchShow = (state, id, data, router) => {
  if(router) {
    state.dispatch(types.ADD_TOAST, {
      success: 'Updating Show'
    })
  }

  Vue.http.patch('/api/shows/' + id, data)
    .then(function (response) {
      // success callback
      const show = response.data
      state.dispatch(types.ADD_SHOW, show.data)
      if(router) {
        router.go('/shows/' + id)
      }
    }, function (response) {
      // error callback
      state.dispatch(types.ADD_TOAST, {
        error: 'Connection Error'
      })
    })
}