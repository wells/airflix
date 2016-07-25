/*
 |--------------------------------------------------------------------------
 |   Airflix
 |--------------------------------------------------------------------------
 */

import Vue from 'vue'
import VueInfiniteScroll from 'vue-infinite-scroll'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import VueMoment from 'vue-moment'
import store from './vuex/store'
import { filterByGenre, filterByHasMovies, filterByHasShows, zeroPad } from './filters'
import { configRouter } from './routes'
import { sync } from 'vuex-router-sync'
import App from './components/App.vue'

// Debug mode
Vue.config.debug = false

// Devtools enabled
Vue.config.devtools = false

// Silence logs and warnings
Vue.config.silent = true

// install resource
Vue.use(VueResource)

Vue.http.headers.common['Accept'] = 
  'application/vnd.api+json; version=1; charset=utf-8'

// install infinite scoll
Vue.use(VueInfiniteScroll)

// install router
Vue.use(VueRouter)

// install vue-moment filter
Vue.use(VueMoment)

// register filters globally
Vue.filter('filterByGenre', filterByGenre)
Vue.filter('filterByHasMovies', filterByHasMovies)
Vue.filter('filterByHasShows', filterByHasShows)
Vue.filter('zeroPad', zeroPad)

// create router
var router = new VueRouter({
  history: true,
  saveScrollPosition: true
})

// synchronize vue-router routes with vuex
sync(store, router)

// configure router
configRouter(router)

// boostrap the app
router.start(App, '#app')
