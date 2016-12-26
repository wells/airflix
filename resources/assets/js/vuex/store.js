import Vue from 'vue'
import Vuex from 'vuex'
import episodes from './modules/episodes'
import filters from './modules/filters'
import genres from './modules/genres'
import images from './modules/images'
import interfaces from './modules/interfaces'
import movies from './modules/movies'
import search from './modules/search'
import seasons from './modules/seasons'
import settings from './modules/settings'
import shows from './modules/shows'
import toasts from './modules/toasts'
import views from './modules/views'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    episodes,
    filters,
  	genres,
  	images,
    interfaces,
    movies,
    search,
    seasons,
    settings,
    shows,
    toasts,
    views
  },
  strict: process.env.NODE_ENV !== 'production'
})
