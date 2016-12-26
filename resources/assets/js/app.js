/*
 |--------------------------------------------------------------------------
 |   Airflix
 |--------------------------------------------------------------------------
 */

import Vue from 'vue'
import _ from 'lodash'
import Promise from 'es6-promise'
import VueRouter from 'vue-router'
import VueMoment from 'vue-moment'
import axios from 'axios'
import store from './vuex/store'
import { zeroPad } from './filters'
import { routes } from './routes'
import { sync } from 'vuex-router-sync'
import App from './components/App.vue'

// Devtools enabled
Vue.config.devtools = false

// Silence logs and warnings
Vue.config.silent = true

// install lodash
window._ = _

// install axios
window.axios = axios

axios.defaults.headers.common['Accept'] = 
  'application/vnd.api+json; version=1; charset=utf-8'

// install router
Vue.use(VueRouter)

// install vue-moment filter
Vue.use(VueMoment)

// register filters globally
Vue.filter('zeroPad', zeroPad)

// create router
const router = new VueRouter({
  mode: 'history',
  routes
})

// synchronize vue-router routes with vuex
sync(store, router)

window.vueRouter = router

const app = new Vue({
	router, 
	store,
	...App
}).$mount('#app')
