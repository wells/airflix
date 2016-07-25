<template>
<div class="movie-edit">
  <spinner v-show="$loadingRouteData" transition="loading"></spinner>

  <div v-if="!$loadingRouteData">
    <h1>Edit Movie</h1>

    <a class="button" v-link="{ path: '/movies/' + movie.id }">
      <i class="material-icons">&#xE5C4;</i> {{ movie.attributes.title }}
    </a>

    <div v-if="isResolved">
      <h2>Movie Title</h2>

      <input class="form-control" placeholder="Title" :value="movie.attributes.title" @input="patchTitle | debounce 1000">

      <ul class="tags">
        <li>
          <div class="key">Original Title:</div>
          <div class="value">{{ movie.attributes.original_title }}</div>
        </li>
        <li>
          <div class="key">Folder Name:</div>
          <div class="value">{{ movie.attributes.folder_name }}</div>
        </li>
      </ul>

      <div v-if="movie.relationships.posters">
        <h2>Posters</h2>
        <ul class="carousel posters">
          <movie-poster v-for="poster in posters" track-by="id" :movie="movie" :poster="poster">
          </movie-poster>
        </ul>
      </div>
      
      <div v-if="movie.relationships.backdrops">
      <h2>Backdrops</h2>
      <ul class="carousel backdrops">
        <movie-backdrop v-for="backdrop in backdrops" track-by="id" :movie="movie" :backdrop="backdrop">
        </movie-backdrop>
      </ul>
      </div>
    </div>

    <div v-if="!isResolved">
      <h2><i class="material-icons">&#xE002;</i> Please Resolve</h2>
      <p>
        Oops! We can't figure out which movie this is. Please make a selection from the search results below.
      </p>
      <ul>
        <li>Folder Name: {{ movie.attributes.folder_name }}</li>
      </ul>
    </div>

    <div v-if="movie.relationships.results">
      <h2>Search Results</h2>
      <ul class="carousel posters">
        <movie-result v-for="result in results" track-by="id" :movie="movie" :result="result">
        </movie-result>
      </ul>
    </div>
  </div>
</div>
</template>

<script>
import MovieBackdrop from './MovieBackdrop.vue'
import MoviePoster from './MoviePoster.vue'
import MovieResult from './MovieResult.vue'
import Spinner from './Spinner.vue'
import { 
  selectMovie, 
  getMovieWithResults, 
  patchMovie
} from '../vuex/actions/movies'

export default {
  name: 'MovieEdit',
  components: {
    MovieBackdrop,
    MoviePoster,
    MovieResult,
    Spinner
  },
  vuex: {
    getters: {
      backdrops: ({ movies, images }) => {
        const movie = movies.all.find(
          m => m.id === movies.currentID
        )
        return movie.relationships.backdrops.data.map(
          ({ id }) => images.all.find(i => i.id === id)
        )
      },
      movie: ({ movies }) => movies.all.find(
        m => m.id === movies.currentID
      ),
      posters: ({ movies, images }) => {
        const movie = movies.all.find(
          m => m.id === movies.currentID
        )
        return movie.relationships.posters.data.map(
          ({ id }) => images.all.find(i => i.id === id)
        )
      },
      results: ({ movies, search }) => {
        const movie = movies.all.find(
          m => m.id === movies.currentID
        )
        return movie.relationships.results.data.map(
          ({ id }) => search.movies.find(m => m.id === id)
        )
      }
    },
    actions: {
      selectMovie,
      getMovieWithResults,
      patchMovie
    }
  },
  route: {
    data: function (transition) {
      this.selectMovie(transition)
      this.getMovieWithResults(transition)
    }
  },
  methods: {
    patchTitle: function (event) {
      const data = {
        'data': {
          'type': 'movies',
          'id': this.movie.id,
          'attributes': {
            'title': event.target.value
          }
        }
      }

      this.patchMovie(this.movie.id, data)
    }
  },
  computed: {
    isResolved: function () {
      return this.movie.attributes.tmdb_movie_id != 0
    },
  }
}
</script>