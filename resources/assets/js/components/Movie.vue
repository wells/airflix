<template>
<div class="movie">
  <spinner v-show="$loadingRouteData" transition="loading"></spinner>

  <div v-if="!$loadingRouteData">
    <img :src="movie.attributes.backdrop_url" />

    <h1>{{ movie.attributes.title }}</h1>

    <a class="button button-desktop" v-link="{ path: '/movies' }">
      <i class="material-icons">&#xE02C;</i> Movies
    </a>
    <a class="button" :class="{ disabled: isDisabled }" href="/downloads/movies/{{ movie.id }}">
      <i class="material-icons">&#xE039;</i> Watch
    </a>
    <a class="button" v-link="{ path: '/movies/' + movie.id + '/edit' }">
      <i class="material-icons">&#xE254;</i> Edit
    </a>

    <div class="overview">
      <h2>
        <i class="material-icons">&#xE0B7;</i>
        Overview
      </h2>

      <img v-if="movie.attributes.poster_url" :src="movie.attributes.poster_url" />

      <p>
      {{ movie.attributes.overview }}
      </p>
    </div>

    <a class="button" target="_blank" :href="movie.attributes.tmdb_url">
      <i class="material-icons">&#xE157;</i> TMDB
    </a>
    <a class="button" target="_blank" v-if="movie.attributes.imdb_url" :href="movie.attributes.imdb_url">
      <i class="material-icons">&#xE157;</i> IMDB
    </a>

    <ul class="tags">
      <li>
        <div class="key">Runtime:</div>
        <div class="value">{{ movie.attributes.runtime }} minutes</div>
      </li>
      <li>
        <div class="key">Release Date:</div>
        <div class="value">{{ movie.attributes.release_date | moment "MMMM Do Y" }}</div>
      </li>
      <li v-if="movie.attributes.budget != '$0'">
        <div class="key">Budget:</div>
        <div class="value">{{ movie.attributes.budget }}</div>
      </li>
      <li v-if="movie.attributes.revenue != '$0'">
        <div class="key">Revenue:</div>
        <div class="value">{{ movie.attributes.revenue }}</div>
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
          <div class="value">{{ movie.attributes.total_views }}</div>
        </li>
        <li>
          <div class="key">Last 12 Months:</div>
          <div class="value">{{ totalViewsLastYear }}</div>
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
import { 
  selectMovie, 
  getMovie 
} from '../vuex/actions/movies'

export default {
  name: 'Movie',
  components: {
    MonthlyChart,
    Spinner 
  },
  vuex: {
    getters: {
      movie: ({ movies }) => movies.all.find(
        m => m.id === movies.currentID
      ),
      genres: ({ movies, genres }) => {
        const movie = movies.all.find(
          m => m.id === movies.currentID
        )
        return movie.relationships.genres.data.map(
          ({ id }) => genres.all.find(g => g.id === id)
        )
      },
      monthlyViews: ({ movies, views }) => {
        const movie = movies.all.find(
          m => m.id === movies.currentID
        )
        const movieViews = movie.relationships.views.data.map(
          ({ id }) => views.movies.find(m => m.id === id)
        )

        // Create a moment.js range of the past 12 months
        const currentMonth = moment().startOf('month')
        const lastYear = currentMonth.clone().subtract(11, 'M')
        const range = moment.range(lastYear, currentMonth)
        const months = []

        // Create records for past 12 months and merge API data
        range.by('months', function(month) {
          const label = month.format('MM/YY')
          const view = movieViews.find(
            v => v.attributes.label === label
          )
          months.push({
            id: label,
            total: view ? view.attributes.total : 0,
          })
        })

        return months
      }
    },
    actions: {
      selectMovie,
      getMovie
    }
  },
  route: {
    data (transition) {
      this.selectMovie(transition)
      this.getMovie(transition)
    }
  },
  computed: {
    totalViewsLastYear: function () {
      return this.monthlyViews.reduce(
        (total, month) => total + month.total, 0
      )
    },
    isDisabled: function () {
      return ! this.movie.attributes.has_file
    }
  }
}
</script>