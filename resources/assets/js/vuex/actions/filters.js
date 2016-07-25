import Vue from 'vue' 
import * as types from '../mutation-types'

// Filters
export const clearFilters = (state) => {
  state.dispatch(types.CLEAR_FILTERS)
}

export const clearGenresFilter = (state) => {
  state.dispatch(types.CLEAR_GENRES_FILTER)
}

export const filterGenres = (state, event) => {
  state.dispatch(types.FILTER_GENRES, event.target.value)
}

export const filterKeywords = (state, event) => {
  state.dispatch(types.FILTER_KEYWORDS, event.target.value)
}

export const filterOrder = (state, event) => {
  state.dispatch(types.FILTER_ORDER, event.target.value)
}