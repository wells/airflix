// Import screens
import MoviesScreen from './components/screens/MoviesScreen.vue'
import MovieScreen from './components/screens/MovieScreen.vue'
import MovieEditScreen from './components/screens/MovieEditScreen.vue'
import SettingsScreen from './components/screens/SettingsScreen.vue'
import ShowsScreen from './components/screens/ShowsScreen.vue'
import ShowScreen from './components/screens/ShowScreen.vue'
import ShowEditScreen from './components/screens/ShowEditScreen.vue'
import SeasonScreen from './components/screens/SeasonScreen.vue'
import NotFoundScreen from './components/screens/NotFoundScreen.vue'
  
export const routes = [
  { path: '/', redirect: '/movies' },
  { path: '/movies', component: MoviesScreen },
  { path: '/movies/:id', component: MovieScreen },
  { path: '/movies/:id/edit', component: MovieEditScreen },
  { path: '/settings', component: SettingsScreen },
  { path: '/shows', component: ShowsScreen },
  { path: '/shows/:id', component: ShowScreen },
  { path: '/shows/:id/edit', component: ShowEditScreen },
  { path: '/shows/seasons/:id', component: SeasonScreen },
  // Not Found Handler
  { path: '*', component: NotFoundScreen }
]
