<template>
<div class="show">
  <spinner v-show="$loadingRouteData" transition="loading"></spinner>

  <div v-if="!$loadingRouteData">
    <img :src="show.attributes.backdrop_url" />
    
    <h1>{{ show.attributes.name }}</h1>

    <a class="button button-desktop" v-link="{ path: '/shows' }">
      <i class="material-icons">&#xE639;</i> Shows
    </a>
    <a class="button" v-link="{ path: '/shows/' + show.id + '/edit' }">
      <i class="material-icons">&#xE254;</i> Edit
    </a>

    <div v-if="show.relationships.seasons">
      <h2>
        <i class="material-icons">&#xE04A;</i>
        Seasons
      </h2>

      <ul class="grid">
        <li v-for="season in seasons | orderBy attributes.season_number">
          <a class="button" v-link="{ path: '/shows/seasons/' + season.id }">
            {{ season.attributes.name }}
          </a>
        </li>
      </ul>
    </div>

    <div class="overview">
      <h2>
        <i class="material-icons">&#xE0B7;</i>
        Overview
      </h2>

      <img v-if="show.attributes.poster_url" :src="show.attributes.poster_url" />

      <p>
      {{ show.attributes.overview }}
      </p>
    </div>

    <a class="button" target="_blank" :href="show.attributes.tmdb_url">
      <i class="material-icons">&#xE157;</i> TMDB
    </a>
    <a class="button" target="_blank" v-if="show.attributes.imdb_url" :href="show.attributes.imdb_url">
      <i class="material-icons">&#xE157;</i> IMDB
    </a>
    <a class="button" target="_blank" v-if="show.attributes.tvdb_url" :href="show.attributes.tvdb_url">
      <i class="material-icons">&#xE157;</i> TVDB
    </a>

    <ul class="tags">
      <li>
        <div class="key">Total Seasons:</div>
        <div class="value">{{ show.attributes.number_of_seasons }}</div>
      </li>
      <li>
        <div class="key">Total Episodes:</div>
        <div class="value">{{ show.attributes.number_of_episodes }}</div>
      </li>
      <li>
        <div class="key">Average Runtime:</div> 
        <div class="value">{{ show.attributes.average_runtime }} minutes</div>
      </li>
      <li>
        <div class="key">First Air Date:</div>
        <div class="value">{{ show.attributes.first_air_date | moment "MMMM Do Y" }}</div>
      </li>
      <li>
        <div class="key">Last Air Date:</div>
        <div class="value">{{ show.attributes.last_air_date | moment "MMMM Do Y" }}</div>
      </li>
    </ul>

    <h2>
      <i class="material-icons">&#xE431;</i>
      Genres
    </h2>

    <ul class="tags" v-if="show.relationships.genres">
      <li v-for="genre in genres">
        {{ genre.attributes.name }}
      </li>
    </ul>

    <div v-if="show.relationships.views">
      <h2>
        <i class="material-icons">&#xE922;</i>
        Stats
      </h2>

      <ul class="tags">
        <li>
          <div class="key">Overall Views:</div>
          <div class="value">{{ show.attributes.total_views }}</div>
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
  selectShow, 
  getShow 
} from '../vuex/actions/shows'

export default {
  name: 'Show',
  components: {
    MonthlyChart,
    Spinner 
  },
  vuex: {
    getters: {
      show: ({ shows }) => shows.all.find(
        s => s.id === shows.currentID
      ),
      genres: ({ shows, genres }) => {
        const show = shows.all.find(
          s => s.id === shows.currentID
        )
        return show.relationships.genres.data.map(
          ({ id }) => genres.all.find(g => g.id === id)
        )
      },
      monthlyViews: ({ shows, views }) => {
        const show = shows.all.find(
          s => s.id === shows.currentID
        )
        const showViews = show.relationships.views.data.map(
          ({ id }) => views.shows.find(s => s.id === id)
        )

        // Create a moment.js range of the past 12 months
        const currentMonth = moment().startOf('month')
        const lastYear = currentMonth.clone().subtract(11, 'M')
        const range = moment.range(lastYear, currentMonth)
        const months = []

        // Create records for past 12 months and merge API data
        range.by('months', function(month) {
          const label = month.format('MM/YY')
          const view = showViews.find(
            s => s.attributes.label === label
          )
          months.push({
            id: label,
            total: view ? view.attributes.total : 0,
          })
        })

        return months
      },
      seasons: ({ shows, seasons }) => {
        const show = shows.all.find(
          s => s.id === shows.currentID
        )
        return show.relationships.seasons.data.map(
          ({ id }) => seasons.all.find(
            s => s.id === id
          )
        )
      }
    },
    actions: {
      selectShow,
      getShow,
    }
  },
  route: {
    data (transition) {
      this.selectShow(transition)
      this.getShow(transition)
    }
  },
  computed: {
    totalViewsLastYear: function () {
      return this.monthlyViews.reduce(
        (total, month) => total + month.total, 0
      )
    }
  }
}
</script>