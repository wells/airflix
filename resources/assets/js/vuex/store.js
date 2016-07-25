import Vue from 'vue'
import Vuex from 'vuex'
import createLogger from 'vuex/logger'
import episodes from './modules/episodes'
import filters from './modules/filters'
import genres from './modules/genres'
import images from './modules/images'
import mobile from './modules/mobile'
import movies from './modules/movies'
import search from './modules/search'
import seasons from './modules/seasons'
import settings from './modules/settings'
import shows from './modules/shows'
import toasts from './modules/toasts'
import views from './modules/views'

Vue.use(Vuex)

const debug = Vue.config.debug

export default new Vuex.Store({
  modules: {
    episodes,
    filters,
  	genres,
  	images,
    mobile,
    movies,
    search,
    seasons,
    settings,
    shows,
    toasts,
    views
  },
  strict: debug,
  middlewares: debug ? [createLogger] : []
})
