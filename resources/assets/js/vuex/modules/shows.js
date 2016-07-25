import { addRecord, addRecords } from '../helpers'
import * as types from '../mutation-types'

// initial state
const state = {
  currentID: null,
  loading: false,
  all: [],
  links: [],
}

// mutations
const mutations = {
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
}

export default {
  state,
  mutations
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