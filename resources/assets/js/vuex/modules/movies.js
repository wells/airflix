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
  [types.LOADING_MOVIES] (state) {
    state.loading = true
  },
  [types.LOADED_MOVIES] (state) {
    state.loading = false
  },
  [types.SELECT_MOVIE] (state, id) {
    state.currentID = id
  },
  [types.ADD_MOVIE_LINKS] (state, links) {
    state.links = links
  },
  [types.ADD_MOVIE] (state, movie) {
    addRecord(state.all, movie, 'movies', updateRelationships)
  },
  [types.ADD_MOVIES] (state, movies) {
    addRecords(state.all, movies, 'movies', updateRelationships)
  }
}

export default {
  state,
  mutations
}

function updateRelationships (record, movie) {
  if(!movie.relationships) {
    return
  }
  record.relationships.backdrops = movie.relationships.backdrops ? 
    movie.relationships.backdrops : record.relationships.backdrops
  record.relationships.genres = movie.relationships.genres ? 
    movie.relationships.genres : record.relationships.genres
  record.relationships.posters = movie.relationships.posters ? 
    movie.relationships.posters : record.relationships.posters
  record.relationships.results = movie.relationships.results ? 
    movie.relationships.results : record.relationships.results
  record.relationships.views = movie.relationships.views ? 
    movie.relationships.views : record.relationships.views
}
