<template>
<div class="movies">
  <transition name="loading">
    <spinner v-show="loadingRouteData"></spinner>
  </transition>

  <!-- search -->
  <div class="search">
    <div class="fields">
      <input class="form-control" 
          placeholder="Keywords" 
          :value="keywords" 
          @input="filterMovieKeywords" />

      <div class="dropdown order">
        <select class="form-control" 
            :value="selectedOrder" 
            @change="filterMovieOrder">
          <option v-for="option in orders" 
              :value="option.id">
            {{ option.name }}
          </option>
        </select>
      </div>

      <div class="dropdown genres">
        <select class="form-control" 
            :value="selectedGenre" 
            @change="filterMovieGenres">
          <option value="">All Genres</option>
          <option v-for="option in genres" 
              :value="option.id">
            {{ option.attributes.name }}
          </option>
        </select>
      </div>
    </div>
    <div class="clear">
      <a class="button" 
          @click.prevent="filterReset">
        Clear
      </a>
    </div>
  </div>

  <!-- results -->
  <div class="results">
    <h1>Movies</h1>
    <div class="cards">
      <movie-card v-for="movie in movies" 
          :key="movie.id" 
          :movie="movie">
      </movie-card>
    </div>
  </div>

  <mugen-scroll :handler="loadMore" 
      :should-handle="!loading">
  </mugen-scroll>
</div>
</template>

<script>
import MovieCard from './MovieCard.vue'
import MugenScroll from 'vue-mugen-scroll'
import Spinner from './Spinner.vue'
import { mapActions } from 'vuex'

export default {
  name: 'Movies',

  components: { 
    MovieCard,
    MugenScroll,
    Spinner
  },

  created: function () {
    this.loadingRoute()
    this.clearGenresFilter()
    this.fetchData()
  },

  watch: {
    '$route': function () {
      this.loadingRoute()
      this.fetchData()
    },

    'links': function () {
      let self = this

      clearTimeout(self.loadingTimeout)

      self.loadingTimeout = setTimeout(function () {
        if(self.links && self.links.next && !self.loading) {
          self.fetchData(self.links.next)
        }
      }, 1000)
    }
  },

  methods: {
    ...mapActions([
      'clearFilters',
      'clearGenresFilter',
      'getMovies',
      'filterGenres',
      'filterKeywords',
      'filterOrder',
      'loadingRoute'
    ]),

    fetchData: function (payloadUrl) {
      if(! payloadUrl) {
        payloadUrl = '/api/movies' + this.$store.state.filters.queryMovies
      }

      let payload = {
        url: payloadUrl
      }

      this.getMovies(payload)
    },

    filterMovieGenres: function (event) {
      this.filterGenres(event)
      this.fetchData()
    },

    filterMovieKeywords: _.debounce(
      function (event) {
        this.filterKeywords(event)
        this.fetchData()
      }, 
      250
    ),

    filterMovieOrder: function (event) {
      this.filterOrder(event)
      this.fetchData()
    },

    filterReset: function () {
      this.clearFilters()
      this.fetchData()
    },

    loadMore: _.throttle(
      function () {
        if (this.links && this.links.next && !this.loading) {
          this.fetchData(this.links.next)
        }
      },
      500
    )
  },

  computed: {
    genres: function () {
      return _.chain(this.$store.state.genres.all)
        .sortBy('attributes.name')
        // Has movies
        .filter(function (item) { 
          return item.attributes.total_movies > 0 
        })
        .value()
    },

    keywords: function () {
      return this.$store.state.filters.keywords
    },

    links: function () {
      return this.$store.state.movies.links
    },

    loading: function () {
      return this.$store.state.movies.loading
    },

    loadingRouteData: function () {
      return this.$store.state.interfaces.loadingRouteData
    },

    movies: function () {
      let state = this.$store.state

      let keywords = state.filters.keywords
      let selectedGenre = state.filters.selectedGenre
      let column = state.filters.attributes.movies
      let direction = state.filters.direction

      return _.chain(state.movies.all)
        // Filter by keywords
        .filter(function (item) {
          let searchRegex = new RegExp(keywords, 'i')
          return searchRegex.test(item.attributes.title)
        })
        // Filter by genre
        .filter(function (item) { 
          let record = item.relationships.genres.data.find(g => g.id == selectedGenre)
          return selectedGenre == '' || record != null
        })
        // Order by column and direction
        .orderBy([column], [direction])
        .value()
    },

    orders: function () {
      return this.$store.state.filters.orders
    },

    selectedGenre: function () {
      return this.$store.state.filters.selectedGenre
    },

    selectedOrder: function () {
      return this.$store.state.filters.selectedOrder
    }
  }
}
</script>