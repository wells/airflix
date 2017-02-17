<template>
<div class="show">
  <transition name="loading">
    <spinner v-show="loadingRouteData"></spinner>
  </transition>

  <div v-if="!loadingRouteData">
    <img :src="show.attributes.backdrop_url" />
    
    <h1>
      {{ show.attributes.name }}
    </h1>

    <router-link class="button button-desktop" 
        to="/shows">
      <i class="material-icons">&#xE639;</i> Shows
    </router-link>
    <router-link class="button" 
        :to="{ path: '/shows/' + show.id + '/edit' }">
      <i class="material-icons">&#xE254;</i> Edit
    </router-link>

    <div v-if="show.relationships.seasons">
      <h2>
        <i class="material-icons">&#xE04A;</i>
        Seasons
      </h2>

      <ul class="grid">
        <li v-for="season in seasons">
          <router-link class="button" 
              :to="{ path: '/shows/seasons/' + season.id }">
            {{ season.attributes.name }}
          </router-link>
        </li>
      </ul>
    </div>

    <div class="overview">
      <h2>
        <i class="material-icons">&#xE0B7;</i>
        Overview
      </h2>

      <img v-if="show.attributes.poster_url" 
          :src="show.attributes.poster_url" />

      <p>
        {{ show.attributes.overview }}
      </p>
    </div>

    <a class="button" 
        target="_blank" 
        :href="show.attributes.tmdb_url">
      <i class="material-icons">&#xE157;</i> TMDB
    </a>
    <a class="button" 
        target="_blank" 
        :href="show.attributes.imdb_url" 
        v-if="show.attributes.imdb_url">
      <i class="material-icons">&#xE157;</i> IMDB
    </a>
    <a class="button" 
        target="_blank" 
        :href="show.attributes.tvdb_url" 
        v-if="show.attributes.tvdb_url">
      <i class="material-icons">&#xE157;</i> TVDB
    </a>

    <ul class="tags">
      <li>
        <div class="key">Total Seasons:</div>
        <div class="value">
          {{ show.attributes.number_of_seasons }}
        </div>
      </li>
      <li>
        <div class="key">Total Episodes:</div>
        <div class="value">
          {{ show.attributes.number_of_episodes }}
        </div>
      </li>
      <li>
        <div class="key">Average Runtime:</div> 
        <div class="value">
          {{ show.attributes.average_runtime }} minutes
        </div>
      </li>
      <li>
        <div class="key">First Air Date:</div>
        <div class="value">
          {{ show.attributes.first_air_date | moment("MMMM Do Y") }}
        </div>
      </li>
      <li>
        <div class="key">Last Air Date:</div>
        <div class="value">
          {{ show.attributes.last_air_date | moment("MMMM Do Y") }}
        </div>
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
          <div class="value">
            {{ show.attributes.total_views }}
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
import MonthlyChart from '../charts/MonthlyChart.vue'
import Spinner from '../statuses/Spinner.vue'
import { mapActions } from 'vuex'

export default {
  name: 'Show',

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
      'getShow',
      'loadingRoute'
    ]),

    fetchData: function () {
      let payload = {
        id: this.$route.params.id,
        url: '/api/shows/' + this.$route.params.id
      }

      this.getShow(payload)
    }
  },

  computed: {
    genres: function () {
      let state = this.$store.state
      let show = state.shows.all.find(
        s => s.id == state.shows.currentID
      )

      return show.relationships.genres.data.map(
        ({ id }) => state.genres.all.find(g => g.id == id)
      )
    },

    loadingRouteData: function () {
      return this.$store.state.interfaces.loadingRouteData
    },

    monthlyViews: function () {
      let state = this.$store.state
      let show = state.shows.all.find(
        s => s.id == state.shows.currentID
      )
      let showViews = show.relationships.views.data.map(
        ({ id }) => state.views.shows.find(s => s.id == id)
      )

      // Create a moment.js range of the past 12 months
      let currentMonth = moment().startOf('month')
      let lastYear = currentMonth.clone().subtract(11, 'M')
      let range = moment.range(lastYear, currentMonth)
      let months = []

      // Create records for past 12 months and merge API data
      range.by('months', function(month) {
        let label = month.format('MM/YY')
        let view = showViews.find(
          s => s.attributes.label == label
        )
        months.push({
          id: label,
          total: view ? view.attributes.total : 0,
        })
      })

      return months
    },

    seasons: function () {
      let state = this.$store.state
      let show = state.shows.all.find(
        s => s.id == state.shows.currentID
      )

      return _.chain(
        // Map season objects to the TV show
        show.relationships.seasons.data.map(
          ({ id }) => state.seasons.all.find(
            s => s.id == id
          )
        )
      )
      // Order by season number
      .orderBy(['attributes.season_number'], ['asc'])
      .value()
    },

    show: function () { 
      let state = this.$store.state

      return state.shows.all.find(
        s => s.id == state.shows.currentID
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