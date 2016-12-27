<template>
<div class="movie">
  <transition name="loading">
    <spinner v-show="loadingRouteData"></spinner>
  </transition>

  <div v-if="!loadingRouteData">
    <img :src="movie.attributes.backdrop_url" />

    <h1>
      {{ movie.attributes.title }}
    </h1>

    <router-link class="button button-desktop" to="/movies">
      <i class="material-icons">&#xE02C;</i> Movies
    </router-link>
    <a class="button" 
        :class="{ disabled: isDisabled }" 
        :href="'/downloads/movies/' + movie.id">
      <i class="material-icons">&#xE039;</i> Watch
    </a>
    <router-link class="button" 
        :to="{ path: '/movies/' + movie.id + '/edit' }">
      <i class="material-icons">&#xE254;</i> Edit
    </router-link>

    <div class="overview">
      <h2>
        <i class="material-icons">&#xE0B7;</i>
        Overview
      </h2>

      <img v-if="movie.attributes.poster_url" 
          :src="movie.attributes.poster_url" />

      <p>
      {{ movie.attributes.overview }}
      </p>
    </div>

    <a class="button" 
        target="_blank" 
        :href="movie.attributes.tmdb_url">
      <i class="material-icons">&#xE157;</i> TMDB
    </a>
    <a class="button" 
        target="_blank" 
        :href="movie.attributes.imdb_url" 
        v-if="movie.attributes.imdb_url">
      <i class="material-icons">&#xE157;</i> IMDB
    </a>

    <ul class="tags">
      <li>
        <div class="key">Runtime:</div>
        <div class="value">
          {{ movie.attributes.runtime }} minutes
        </div>
      </li>
      <li>
        <div class="key">Release Date:</div>
        <div class="value">
          {{ movie.attributes.release_date | moment("MMMM Do Y") }}
        </div>
      </li>
      <li v-if="movie.attributes.budget != '$0'">
        <div class="key">Budget:</div>
        <div class="value">
          {{ movie.attributes.budget }}
        </div>
      </li>
      <li v-if="movie.attributes.revenue != '$0'">
        <div class="key">Revenue:</div>
        <div class="value">
          {{ movie.attributes.revenue }}
        </div>
      </li>
    </ul>

    <h2>
      <i class="material-icons">&#xE431;</i>
      Genres
    </h2>

    <ul class="tags" v-if="movie.relationships.genres">
      <li v-for="genre in genres">
        {{ genre.attributes.name }}
      </li>
    </ul>

    <div v-if="movie.relationships.views">
      <h2>
        <i class="material-icons">&#xE922;</i>
        Stats
      </h2>

      <ul class="tags">
        <li>
          <div class="key">Overall Views:</div>
          <div class="value">
            {{ movie.attributes.total_views }}
          </div>
        </li>
        <li>
          <div class="key">Last 12 Months:</div>
          <div class="value">
            {{ totalViewsLastYear }}
          </div>
        </li>
      </ul>
      <monthly-chart :months="monthlyViews"></monthly-chart>
    </div>
  </div>
</div>
</template>

<script>
import moment from 'moment'
import range from 'moment-range'
import MonthlyChart from './MonthlyChart.vue'
import Spinner from './Spinner.vue'
import { mapActions } from 'vuex'

export default {
  name: 'Movie',

  components: {
    MonthlyChart,
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
      'getMovie',
      'loadingRoute'
    ]),

    fetchData: function () {
      let payload = {
        id: this.$route.params.id,
        url: '/api/movies/' + this.$route.params.id
      }

      this.getMovie(payload)
    }
  },

  computed: {
    isDisabled: function () {
      return ! this.movie.attributes.has_file
    },

    genres: function () {
      let state = this.$store.state
      let movie = state.movies.all.find(
        m => m.id == state.movies.currentID
      )

      return movie.relationships.genres.data.map(
        ({ id }) => state.genres.all.find(g => g.id == id)
      )
    },

    loadingRouteData: function () {
      return this.$store.state.interfaces.loadingRouteData
    },

    monthlyViews: function () {
      let state = this.$store.state
      let movie = state.movies.all.find(
        m => m.id == state.movies.currentID
      )
      let movieViews = movie.relationships.views.data.map(
        ({ id }) => state.views.movies.find(m => m.id == id)
      )

      // Create a moment.js range of the past 12 months
      let currentMonth = moment().startOf('month')
      let lastYear = currentMonth.clone().subtract(11, 'M')
      let range = moment.range(lastYear, currentMonth)
      let months = []

      // Create records for past 12 months and merge API data
      range.by('months', function(month) {
        let label = month.format('MM/YY')
        let view = movieViews.find(
          v => v.attributes.label == label
        )

        months.push({
          id: label,
          total: view ? view.attributes.total : 0,
        })
      })

      return months
    },

    movie: function () {
      let state = this.$store.state

      return state.movies.all.find(
        m => m.id == state.movies.currentID
      )
    },

    totalViewsLastYear: function () {
      return this.monthlyViews.reduce(
        (total, month) => total + month.total, 0
      )
    }
  }
}
</script>