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
  },
  actions: {
    getMovies (context, payload) {
      context.commit(types.LOADING_MOVIES)

      // GET /api/movies
      axios.get(payload.url)
        .then(function (response) {
          // success callback
          let movies = response.data
          context.commit(types.ADD_MOVIES, movies.data)
          context.commit(types.ADD_MOVIE_LINKS, movies.links)
          context.commit(types.ADD_GENRES, movies.included)
          context.commit(types.LOADED_MOVIES)
          context.commit(types.LOADED_ROUTE)
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    getMovie (context, payload) {
      context.commit(types.SELECT_MOVIE, payload.id)

      // GET /api/movies/{id}
      axios.get(payload.url)
        .then(function (response) {
          // success callback
          let movie = response.data
          context.commit(types.ADD_MOVIE, movie.data)
          context.commit(types.ADD_GENRES, movie.included)
          context.commit(types.ADD_MOVIE_VIEWS, movie.included)
          context.commit(types.LOADED_ROUTE)
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    getMovieWithResults (context, payload) {
      context.commit(types.SELECT_MOVIE, payload.id)

      // GET /api/movies/{id}?include=backdrops,posters,results
      axios.get(payload.url)
        .then(function (response) {
          // success callback 
          let movie = response.data
          context.commit(types.ADD_MOVIE, movie.data)
          context.commit(types.ADD_IMAGES, movie.included)
          context.commit(types.ADD_MOVIE_RESULTS, movie.included)
          context.commit(types.LOADED_ROUTE)
        })
        .catch(function (error) {
          // error callback
          context.commit(types.ADD_TOAST, {
            error: 'Connection Error'
          })
        })
    },

    patchMovie (context, payload) {
      if(payload.redirect) {
        context.commit(types.ADD_TOAST, {
          success: 'Updating Movie'
        })
      }
      
      // PATCH /api/movies/{id}
      axios.patch(payload.url, payload.json)
        .then(function (response) {
          // success callback
          let movie = response.data
          context.commit(types.ADD_MOVIE, movie.data)

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
