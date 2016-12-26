import * as types from '../mutation-types'

export default {
  state: {
    showMenu: false,
    showSearch: false,
    loadingRouteData: true,
  },
  mutations: {
    [types.LOADING_ROUTE] (state) {
      state.loadingRouteData = true
    },
    [types.LOADED_ROUTE] (state) {
      state.loadingRouteData = false
    },
    [types.HIDE_MENU] (state) {
      state.showMenu = false
    },
    [types.TOGGLE_MENU] (state) {
      state.showMenu = ! state.showMenu
    },
    [types.TOGGLE_SEARCH] (state) {
      state.showSearch = ! state.showSearch
    }
  },
  actions: {
    loadingRoute (context) {
      context.commit(types.LOADING_ROUTE)
    },
    loadedRoute (context) {
      context.commit(types.LOADED_ROUTE)
    },
    hideMenu (context) {
      context.commit(types.HIDE_MENU)
    },
    toggleMenu (context) {
      context.commit(types.TOGGLE_MENU)
    },
    toggleSearch (context) {
      context.commit(types.TOGGLE_SEARCH)
    }
  }
}
