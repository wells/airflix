import Vue from 'vue' 
import * as types from '../mutation-types'

export const clearAll = (state) => { 
  Vue.http.delete('/api/settings/history?all')
    .then(function (response) {
      // success callback
      state.dispatch(types.ADD_TOAST, {
        success: 'Clearing All History'
      })
    }, function (response) {
      // error callback
      state.dispatch(types.ADD_TOAST, {
        error: 'Connection Error'
      })
    })
}

export const clearToday = (state) => { 
  Vue.http.delete('/api/settings/history')
    .then(function (response) {
      // success callback
      state.dispatch(types.ADD_TOAST, {
        success: 'Clearing Today\'s History'
      })
    }, function (response) {
      // error callback
      state.dispatch(types.ADD_TOAST, {
        error: 'Connection Error'
      })
    })
}

// Shows
export const getSettings = (state, transition) => {
  Vue.http.get('/api/settings')
    .then(function (response) {
      // success callback
      const settings = response.data
      state.dispatch(types.ADD_SETTINGS, settings.data)
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

export const patchSettings = (state, data) => {
  Vue.http.patch('/api/settings', data)
    .then(function (response) {
      // success callback
      const settings = response.data
      state.dispatch(types.ADD_SETTINGS, settings.data)
      state.dispatch(types.CLEAR_ALL)
      state.dispatch(types.ADD_TOAST, {
        success: 'Updating Folders'
      })
    }, function (response) {
      // error callback
      state.dispatch(types.ADD_TOAST, {
        error: 'Connection Error'
      })
    })
}

export const refreshAll = (state) => { 
  Vue.http.patch('/api/settings/folders?all')
    .then(function (response) {
      // success callback
      state.dispatch(types.ADD_TOAST, {
        success: 'Refreshing All Folders'
      })
    }, function (response) {
      // error callback
      state.dispatch(types.ADD_TOAST, {
        error: 'Connection Error'
      })
    })
}

export const refreshNew = (state) => { 
  Vue.http.patch('/api/settings/folders')
    .then(function (response) {
      // success callback
      state.dispatch(types.ADD_TOAST, {
        success: 'Refreshing New Folders'
      })
    }, function (response) {
      // error callback
      state.dispatch(types.ADD_TOAST, {
        error: 'Connection Error'
      })
    })
}

export const setMoviesFolder = (state, event) => {
  var data = { 'movies_folder': event.target.value }

  state.dispatch(types.SET_SETTINGS_ATTRIBUTES, data)
}

export const setShowsFolder = (state, event) => {
  var data = { 'shows_folder': event.target.value }

  state.dispatch(types.SET_SETTINGS_ATTRIBUTES, data)
}