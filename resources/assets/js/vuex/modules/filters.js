import * as types from '../mutation-types'

// initial state
const state = {
  genre: '',
  keywords: '',
  order: 'a-z',
  attributes: {
    movies: 'attributes.title',
    shows: 'attributes.name'
  },
  queryMovies: '',
  queryShows: '',
  direction: 1,
  orders: [
    {
      id: 'a-z',
      name: 'Title: A-Z',
      prefix: '',
      movies: 'title',
      shows: 'name',
      direction: 1,
    },
    {
      id: 'z-a',
      name: 'Title: Z-A',
      prefix: '-',
      movies: 'title',
      shows: 'name',
      direction: -1
    },
    {
      id: 'newest',
      name: 'Year: Newest',
      prefix: '-',
      movies: 'release_date',
      shows: 'first_air_date',
      direction: -1
    },
    {
      id: 'oldest',
      name: 'Year: Oldest',
      prefix: '',
      movies: 'release_date',
      shows: 'first_air_date',
      direction: 1
    }
  ]
}

// mutations
const mutations = {
  [types.CLEAR_FILTERS] (state) {
    state.genre = ''
    state.keywords = ''
    state.order = 'a-z'
    buildFilters()
  },
  [types.CLEAR_GENRES_FILTER] (state) {
    state.genre = ''
    buildFilters()
  },
  [types.FILTER_GENRES] (state, genre) {
    state.genre = genre
    buildFilters()
  },
  [types.FILTER_KEYWORDS] (state, keywords) {
    state.keywords = keywords
    buildFilters()
  },
  [types.FILTER_ORDER] (state, order) {
    state.order = order
    buildFilters()
  }
}

export default {
  state,
  mutations
}

function buildFilters () {
  const order = state.orders.find(o => o.id === state.order)

  state.attributes.movies = 'attributes.' + order.movies
  state.attributes.shows = 'attributes.' + order.shows
  state.direction = order.direction

  buildMovieQuery(order)
  buildShowQuery(order)
}

function buildMovieQuery (order) {
  state.queryMovies = buildQuery(order.prefix + order.movies)
}

function buildShowQuery (order) {
  state.queryShows = buildQuery(order.prefix + order.shows)
}

function buildQuery (order) {
  const parameters = [
    { key: 'keywords', value: state.keywords },
    { key: 'sort', value: order },
    { key: 'genre', value: state.genre }
  ]
  var query = ""
  parameters.forEach(param => {
    if(shouldAddParameter(param)) {
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

function shouldAddParameter (param) {
  if(param.key == 'sort' && state.order == 'a-z') {
    return false
  }

  return param.value != ''
}