<template>
<div class="movie-edit">
  <transition name="loading">
    <spinner v-show="loadingRouteData"></spinner>
  </transition>

  <div v-if="!loadingRouteData">
    <h1>Edit Movie</h1>

    <router-link class="button" 
        :to="{ path: '/movies/' + movie.id }">
      <i class="material-icons">&#xE5C4;</i> {{ movie.attributes.title }}
    </router-link>

    <div v-if="isResolved">
      <h2>Movie Title</h2>

      <input class="form-control" 
          placeholder="Title" 
          :value="movie.attributes.title" 
          @input="patchTitle" />

      <ul class="tags">
        <li>
          <div class="key">Original Title:</div>
          <div class="value">
            {{ movie.attributes.original_title }}
          </div>
        </li>
        <li>
          <div class="key">Folder Name:</div>
          <div class="value">
            {{ movie.attributes.folder_name }}
          </div>
        </li>
      </ul>

      <div v-if="movie.relationships.posters">
        <h2>Posters</h2>
        <ul class="carousel posters">
          <movie-poster v-for="poster in posters" 
              :key="poster.id" 
              :movie="movie" 
              :poster="poster">
          </movie-poster>
        </ul>
      </div>
      
      <div v-if="movie.relationships.backdrops">
      <h2>Backdrops</h2>
      <ul class="carousel backdrops">
        <movie-backdrop v-for="backdrop in backdrops" 
            :key="backdrop.id" 
            :movie="movie" 
            :backdrop="backdrop">
        </movie-backdrop>
      </ul>
      </div>
    </div>

    <div v-if="!isResolved">
      <h2>
        <i class="material-icons">&#xE002;</i> Please Resolve
      </h2>
      <p>
        Oops! We can't figure out which movie this is. Please make a selection 
        from the search results below.
      </p>
      <ul class="tags">
        <li>
          <div class="key">Folder Name:</div>
          <div class="value">
            {{ movie.attributes.folder_name }}
          </div>
        </li>
      </ul>
    </div>

    <div v-if="movie.relationships.results">
      <h2>Search Results</h2>
      <ul class="carousel posters">
        <movie-result v-for="result in results" 
            :key="result.id" 
            :movie="movie" 
            :result="result">
        </movie-result>
      </ul>
    </div>
  </div>
</div>
</template>

<script>
import MovieBackdrop from '../partials/MovieBackdrop.vue'
import MoviePoster from '../partials/MoviePoster.vue'
import MovieResult from '../partials/MovieResult.vue'
import Spinner from '../statuses/Spinner.vue'
import { mapActions } from 'vuex'

export default {
  name: 'MovieEdit',

  components: {
    MovieBackdrop,
    MoviePoster,
    MovieResult,
    Spinner
  },

  created: function () {
    this.loadingRoute()
    this.fetchData()
  },

  watch: {
    '$route': function () {
      this.loadingRoute()
      this.fetchData()
    }
  },

  methods: {
    ...mapActions([
      'getMovieWithResults',
      'loadingRoute',
      'patchMovie'
    ]),

    fetchData: function () {
      let payload = {
        id: this.$route.params.id,
        url: '/api/movies/' + this.$route.params.id + 
          '?include=backdrops,posters,results'
      }

      this.getMovieWithResults(payload)
    },

    patchTitle: _.debounce(
      function (event) {
        let payload = {
          url: '/api/movies/' + this.movie.id,
          json: {
            data: {
              type: 'movies',
              id: this.movie.id,
              attributes: {
                title: event.target.value
              }
            }
          }
        }

        this.patchMovie(payload)
      },
      500
    )
  },

  computed: {
    backdrops: function () {
      let state = this.$store.state
      let movie = state.movies.all.find(
        m => m.id == state.movies.currentID
      )

      return movie.relationships.backdrops.data.map(
        ({ id }) => state.images.all.find(i => i.id == id)
      )
    },

    isResolved: function () {
      return this.movie.attributes.tmdb_movie_id != 0
    },

    loadingRouteData: function () {
      return this.$store.state.interfaces.loadingRouteData
    },

    movie: function () {
      let state = this.$store.state

      return state.movies.all.find(
        m => m.id == state.movies.currentID
      )
    },

    posters: function () {
      let state = this.$store.state
      let movie = state.movies.all.find(
        m => m.id == state.movies.currentID
      )

      return movie.relationships.posters.data.map(
        ({ id }) => state.images.all.find(i => i.id == id)
      )
    },

    results: function () {
      let state = this.$store.state
      let movie = state.movies.all.find(
        m => m.id == state.movies.currentID
      )

      return movie.relationships.results.data.map(
        ({ id }) => state.search.movies.find(m => m.id == id)
      )
    }
  }
}
</script>