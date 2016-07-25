<template>
<div class="movies" v-infinite-scroll="loadMore()" infinite-scroll-disabled="loading" infinite-scroll-distance="200">

  <spinner v-show="$loadingRouteData" transition="loading"></spinner>

  <!-- search -->
  <div class="search">
    <div class="fields">
      <input class="form-control" placeholder="Keywords" :value="keywords" @input="filterMovieKeywords | debounce 250">

      <div class="dropdown order">
        <select class="form-control" :value="order" @change="filterMovieOrder">
          <option v-for="option in orders" :value="option.id">{{ option.name }}</option>
        </select>
      </div>

      <div class="dropdown genres">
        <select class="form-control" :value="selectedGenre" @change="filterMovieGenres">
          <option value="">All Genres</option>
          <option v-for="option in genres | filterByHasMovies | orderBy 'attributes.name'" :value="option.id">{{ option.attributes.name }}</option>
        </select>
      </div>
    </div>
    <div class="clear">
      <a class="button" @click.prevent="filterReset">Clear</a>
    </div>
  </div>

  <!-- results -->
  <div class="results">
    <h1>Movies</h1>
    <div class="cards">
      <movie-card v-for="movie in movies | filterBy keywords in 'attributes.title' | filterByGenre selectedGenre | orderBy column direction" track-by="id" :movie="movie">
      </movie-card>
    </div>
  </div>
</div>
</template>

<script>
import MovieCard from './MovieCard.vue'
import Spinner from './Spinner.vue'
import { getMovies } from '../vuex/actions/movies'
import {
  clearFilters,
  clearGenresFilter, 
  filterGenres, 
  filterKeywords, 
  filterOrder 
} from '../vuex/actions/filters'

export default {
  name: 'Movies',
  components: { 
    MovieCard,
    Spinner
  },
  ready: function () {
    this.$watch('links.next', function () {
      // Automatically load paginated results
      setTimeout(() => {
        if(this.links.next && !this.loading) {
          this.getMovies(this.links.next)
        }
      }, 1000);
    }, { deep: true })
  },
  vuex: {
    getters: {
      column: ({ filters }) => filters.attributes.movies,
      direction: ({ filters }) => filters.direction,
      genres: ({ genres }) => genres.all,
      keywords: ({ filters }) => filters.keywords,
      links: ({ movies }) => movies.links,
      loading: ({ movies }) => movies.loading,
      movies: ({ movies }) => movies.all,
      order: ({ filters }) => filters.order,
      orders: ({ filters }) => filters.orders,
      query: ({ filters }) => filters.queryMovies,
      selectedGenre: ({ filters }) => filters.genre
    },
    actions: {
      clearFilters,
      clearGenresFilter,
      getMovies,
      filterGenres,
      filterKeywords,
      filterOrder
    }
  },
  route: {
    data: function (transition) {
      this.clearGenresFilter()
      this.getMovies(this.url, transition)
    }
  },
  methods: {
    loadMore: function () {
      if (this.links.next && !this.loading && !this._inactive) {
        this.getMovies(this.links.next)
      }
    },
    filterMovieGenres: function (event) {
      this.filterGenres(event)
      this.getMovies(this.url)
    },
    filterMovieKeywords: function (event) {
      this.filterKeywords(event)
      this.getMovies(this.url)
    },
    filterMovieOrder: function (event) {
      this.filterOrder(event)
      this.getMovies(this.url)
    },
    filterReset: function () {
      this.clearFilters()
      this.getMovies(this.url)
    }
  },
  computed: {
    url: function() {
      return '/api/movies' + this.query
    }
  }
}
</script>