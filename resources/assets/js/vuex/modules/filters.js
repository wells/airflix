import * as types from '../mutation-types'

export default {
  state: {
    selectedGenre: '',
    keywords: '',
    selectedOrder: 'a-z',
    attributes: {
      movies: 'attributes.title',
      shows: 'attributes.name'
    },
    queryMovies: '',
    queryShows: '',
    direction: 'asc',
    orders: [
      {
        id: 'a-z',
        name: 'Title: A-Z',
        prefix: '',
        movies: 'title',
        shows: 'name',
        direction: 'asc',
      },
      {
        id: 'z-a',
        name: 'Title: Z-A',
        prefix: '-',
        movies: 'title',
        shows: 'name',
        direction: 'desc'
      },
      {
        id: 'newest',
        name: 'Year: Newest',
        prefix: '-',
        movies: 'release_date',
        shows: 'first_air_date',
        direction: 'desc'
      },
      {
        id: 'oldest',
        name: 'Year: Oldest',
        prefix: '',
        movies: 'release_date',
        shows: 'first_air_date',
        direction: 'asc'
      }
    ]
  },
  mutations: {
    [types.CLEAR_FILTERS] (state) {
      state.selectedGenre = ''
      state.keywords = ''
      state.selectedOrder = 'a-z'
      buildFilters(state)
    },

    [types.CLEAR_GENRES_FILTER] (state) {
      state.selectedGenre = ''
      buildFilters(state)
    },

    [types.FILTER_GENRES] (state, selectedGenre) {
      state.selectedGenre = selectedGenre
      buildFilters(state)
    },

    [types.FILTER_KEYWORDS] (state, keywords) {
      state.keywords = keywords
      buildFilters(state)
    },

    [types.FILTER_ORDER] (state, selectedOrder) {
      state.selectedOrder = selectedOrder
      buildFilters(state)
    }
  },
  actions: {
    clearFilters (context) {
      context.commit(types.CLEAR_FILTERS)
    },

    clearGenresFilter (context) {
      context.commit(types.CLEAR_GENRES_FILTER)
    },

    filterGenres (context, event) {
      context.commit(types.FILTER_GENRES, event.target.value)
    },

    filterKeywords (context, event) {
      context.commit(types.FILTER_KEYWORDS, event.target.value)
    },

    filterOrder (context, event) {
      context.commit(types.FILTER_ORDER, event.target.value)
    }
  }
}

function buildFilters (state) {
  let order = state.orders.find(o => o.id === state.selectedOrder)

  state.attributes.movies = 'attributes.' + order.movies
  state.attributes.shows = 'attributes.' + order.shows
  state.direction = order.direction

  buildMovieQuery(state, order)
  buildShowQuery(state, order)
}

function buildMovieQuery (state, order) {
  let sort = order.prefix + order.movies
  state.queryMovies = buildQuery(state, sort)
}

function buildShowQuery (state, order) {
  let sort = order.prefix + order.shows
  state.queryShows = buildQuery(state, sort)
}

function buildQuery (state, sort) {
  let parameters = [
    { key: 'keywords', value: state.keywords },
    { key: 'sort', value: sort },
    { key: 'genre', value: state.selectedGenre }
  ]
  let query = ""

  parameters.forEach(param => {
    if(shouldAddParameter(state, param)) {
      query += encodeURIComponent(param.key) + 
        "=" + encodeURIComponent(param.value) + "&"
    }
  })

  if (query.length > 0){
    //chop off last "&"
    query = query.substring(0, query.length-1)
    query = "?" + query
  }

  return query
}

function shouldAddParameter (state, param) {
  if(param.key == 'sort' && state.selectedOrder == 'a-z') {
    return false
  }

  return param.value != ''
}