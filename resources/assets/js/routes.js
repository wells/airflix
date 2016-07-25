// Import components
import Movies from './components/Movies.vue'
import Movie from './components/Movie.vue'
import MovieEdit from './components/MovieEdit.vue'
import Settings from './components/Settings.vue'
import Shows from './components/Shows.vue'
import Show from './components/Show.vue'
import ShowEdit from './components/ShowEdit.vue'
import Season from './components/Season.vue'
import NotFound from './components/NotFound.vue'
  
export function configRouter (router) {
  // routes
  router.map({
    '/movies': {
      component: Movies
    },
    '/movies/:id': {
      component: Movie
    },
    '/movies/:id/edit': {
      component: MovieEdit
    },
    '/settings': {
      component: Settings
    },
    '/shows': {
      component: Shows
    },
    '/shows/:id': {
      component: Show
    },
    '/shows/:id/edit': {
      component: ShowEdit
    },
    '/shows/seasons/:id': {
      component: Season
    },
    // not found handler
    '*': {
      component: NotFound
    },
  })

  // redirects
  router.redirect({
    '/': '/movies'
  })
}
