import { addRecord, addRecords } from '../helpers'
import * as types from '../mutation-types'

export default {
  state: {
    currentID: null,
    loading: false,
    all: [],
    links: [],
  },
  mutations: {
    [types.CLEAR_ALL] (state) {
      state.all = []
    },
    [types.LOADING_SHOWS] (state) {
      state.loading = true
    },
    [types.LOADED_SHOWS] (state) {
      state.loading = false
    },
    [types.SELECT_SHOW] (state, id) {
      state.currentID = id
    },
    [types.ADD_SHOW_LINKS] (state, links) {
      state.links = links
    },
    [types.ADD_SHOW] (state, show) {
      addRecord(state.all, show, 'shows', updateRelationships)
    },
    [types.ADD_SHOWS] (state, shows) {
      addRecords(state.all, shows, 'shows', updateRelationships)
    }
  },
  actions: {
    getShows (context, payload) {
      context.commit(types.LOADING_SHOWS)

      // GET /api/shows
      axios.get(payload.url)
        .then(function (response) {
          // success callback
          let shows = response.data
          context.commit(types.ADD_SHOWS, shows.data)
          context.commit(types.ADD_SHOW_LINKS, shows.links)
          context.commit(types.ADD_GENRES, shows.included)
          context.commit(types.LOADED_SHOWS)
          context.commit(types.LOADED_ROUTE)
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    getShow (context, payload) {
      context.commit(types.SELECT_SHOW, payload.id)

      // GET /api/shows/{id}
      axios.get(payload.url)
        .then(function (response) {
          // success callback
          let show = response.data
          context.commit(types.ADD_SHOW, show.data)
          context.commit(types.ADD_GENRES, show.included)
          context.commit(types.ADD_SEASONS, show.included)
          context.commit(types.ADD_SHOW_VIEWS, show.included)
          context.commit(types.LOADED_ROUTE)
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    getShowWithResults (context, payload) {
      context.commit(types.SELECT_SHOW, payload.id)

      // GET /api/shows/{id}?include=backdrops,posters,results
      axios.get(payload.url)
        .then(function (response) {
          // success callback
          let show = response.data
          context.commit(types.ADD_SHOW, show.data)
          context.commit(types.ADD_GENRES, show.included)
          context.commit(types.ADD_SEASONS, show.included)
          context.commit(types.ADD_IMAGES, show.included)
          context.commit(types.ADD_SHOW_RESULTS, show.included)
          context.commit(types.LOADED_ROUTE)
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    patchShow (context, payload) {
      if(payload.redirect) {
        context.commit(types.ADD_TOAST, {
          success: 'Updating Show'
        })
      }

      // PATCH /api/shows/{id}
      axios.patch(payload.url, payload.json)
        .then(function (response) {
          // success callback
          let show = response.data
          context.commit(types.ADD_SHOW, show.data)

          if(payload.redirect) {
            window.vueRouter.push(payload.redirect)
          }
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

function updateRelationships (record, show) {
  if(!show.relationships) {
    return
  }
  record.relationships.backdrops = show.relationships.backdrops ? 
    show.relationships.backdrops : record.relationships.backdrops
  record.relationships.genres = show.relationships.genres ? 
    show.relationships.genres : record.relationships.genres
  record.relationships.posters = show.relationships.posters ? 
    show.relationships.posters : record.relationships.posters
  record.relationships.results = show.relationships.results ? 
    show.relationships.results : record.relationships.results
  record.relationships.seasons = show.relationships.seasons ? 
    show.relationships.seasons : record.relationships.seasons
  record.relationships.views = show.relationships.views ? 
    show.relationships.views : record.relationships.views
}