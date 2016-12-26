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
  
export const routes = [
  { path: '/', redirect: '/movies' },
  { path: '/movies', component: Movies },
  { path: '/movies/:id', component: Movie },
  { path: '/movies/:id/edit', component: MovieEdit },
  { path: '/settings', component: Settings },
  { path: '/shows', component: Shows },
  { path: '/shows/:id', component: Show },
  { path: '/shows/:id/edit', component: ShowEdit },
  { path: '/shows/seasons/:id', component: Season },
  // Not Found Handler
  { path: '*', component: NotFound }
]
