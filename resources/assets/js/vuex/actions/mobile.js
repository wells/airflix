import Vue from 'vue' 
import * as types from '../mutation-types'

// Mobile Menus
export const hideMenu = (state) => {
  state.dispatch(types.HIDE_MENU)
}

export const toggleMenu = (state) => {
  state.dispatch(types.TOGGLE_MENU)
}

export const toggleSearch = (state) => {
  state.dispatch(types.TOGGLE_SEARCH)
}