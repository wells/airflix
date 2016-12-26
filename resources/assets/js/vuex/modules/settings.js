import * as types from '../mutation-types'

export default {
  state: {
    current: null,
  },
  mutations: {
    [types.ADD_SETTINGS] (state, settings) {
      state.current = settings
    },

    [types.SET_SETTINGS_ATTRIBUTES] (state, data) {
      for (var name in data) {
        state.current.attributes[name] = data[name]
      }
    }
  },
  actions: {
    clearAll (context, payload) { 
      // DELETE /api/settings/history?all
      axios.delete(payload.url)
        .then(function (response) {
          // success callback
          context.commit(types.ADD_TOAST, {
            success: 'Clearing All History'
          })
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    clearToday (context, payload) { 
      // DELETE /api/settings/history
      axios.delete(payload.url)
        .then(function (response) {
          // success callback
          context.commit(types.ADD_TOAST, {
            success: 'Clearing Today\'s History'
          })
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    getSettings (context, payload) {
      // GET /api/settings
      axios.get(payload.url)
        .then(function (response) {
          // success callback
          let settings = response.data
          context.commit(types.ADD_SETTINGS, settings.data)
          context.commit(types.LOADED_ROUTE)
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    patchSettings (context, payload) {
      // PATCH /api/settings
      axios.patch(payload.url, payload.json)
        .then(function (response) {
          // success callback
          let settings = response.data
          context.commit(types.ADD_SETTINGS, settings.data)
          context.commit(types.CLEAR_ALL)
          context.commit(types.ADD_TOAST, {
            success: 'Updating Folders'
          })
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    refreshAll (context, payload) {
      // PATCH /api/settings/folders?all
      axios.patch(payload.url)
        .then(function (response) {
          // success callback
          context.commit(types.ADD_TOAST, {
            success: 'Refreshing All Folders'
          })
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    refreshNew (context, payload) {
      // PATCH /api/settings/folders
      axios.patch(payload.url)
        .then(function (response) {
          // success callback
          context.commit(types.ADD_TOAST, {
            success: 'Refreshing New Folders'
          })
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    setMoviesFolder (context, event) {
      var data = { 
        movies_folder: event.target.value 
      }

      context.commit(types.SET_SETTINGS_ATTRIBUTES, data)
    },

    setShowsFolder (context, event) {
      var data = { 
        shows_folder: event.target.value 
      }

      context.commit(types.SET_SETTINGS_ATTRIBUTES, data)
    }
  }
}