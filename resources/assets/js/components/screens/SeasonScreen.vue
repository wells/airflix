<template>
<div class="season">
  <transition name="loading">
    <spinner v-show="loadingRouteData"></spinner>
  </transition>

  <div v-if="!loadingRouteData">
    <img :src="show.attributes.backdrop_url" />

    <h1>
      {{ show.attributes.name }} &ndash; {{ season.attributes.name }}
    </h1>

    <router-link class="button"
        :to="{ path: '/shows/' + show.id }">
      <i class="material-icons">&#xE5C4;</i> {{ show.attributes.name }}
    </router-link>

    <div class="overview" v-if="season.attributes.overview">
      <h2>
        <i class="material-icons">&#xE0B7;</i>
        Overview
      </h2>

      <img v-if="season.attributes.poster_url"
          :src="season.attributes.poster_url" />

      <p>
        {{ season.attributes.overview }}
      </p>
    </div>

    <h2>
      <i class="material-icons">&#xE04A;</i>
      Episodes
    </h2>

    <ul class="summaries" v-if="season.relationships.episodes">
      <episode v-for="episode in episodes"
          :key="episode.id"
          :episode="episode">
      </episode>
    </ul>

    <div v-if="season.relationships.views">
      <h2>
        <i class="material-icons">&#xE922;</i>
        Stats
      </h2>

      <ul class="tags">
        <li>
          <div class="key">Overall Views:</div>
          <div class="value">
            {{ season.attributes.total_views }}
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
import Episode from '../partials/Episode.vue'
import MonthlyChart from '../charts/MonthlyChart.vue'
import Spinner from '../statuses/Spinner.vue'
import { mapActions } from 'vuex'

export default {
  name: 'Season',

  components: {
    Episode,
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
      'getSeason',
      'loadingRoute'
    ]),

    fetchData: function () {
      let payload = {
        id: this.$route.params.id,
        url: '/api/seasons/' + this.$route.params.id
      }

      this.getSeason(payload)
    }
  },

  computed: {
    episodes: function () {
      let state = this.$store.state
      let season = state.seasons.all.find(
        s => s.id == state.seasons.currentID
      )

      return season.relationships.episodes.data.map(
        ({ id }) => state.episodes.all.find(e => e.id == id)
      )
    },

    loadingRouteData: function () {
      return this.$store.state.interfaces.loadingRouteData
    },

    monthlyViews: function () {
      let state = this.$store.state
      let season = state.seasons.all.find(
        s => s.id == state.seasons.currentID
      )
      let seasonViews = season.relationships.views.data.map(
        ({ id }) => state.views.seasons.find(s => s.id == id)
      )

      // Create a moment.js range of the past 12 months
      let currentMonth = moment().startOf('month')
      let lastYear = currentMonth.clone().subtract(11, 'M')
      let range = moment.range(lastYear, currentMonth)
      let months = []

      // Create records for past 12 months and merge API data
      range.by('months', function(month) {
        let label = month.format('MM/YY')
        let view = seasonViews.find(
          s => s.attributes.label == label
        )
        months.push({
          id: label,
          total: view ? view.attributes.total : 0,
        })
      })

      return months
    },

    season: function () {
      let state = this.$store.state

      return state.seasons.all.find(
        s => s.id == state.seasons.currentID
      )
    },

    show: function () {
      let state = this.$store.state
      let season = state.seasons.all.find(
        s => s.id == state.seasons.currentID
      )

      return state.shows.all.find(
        s => s.id == season.relationships.show.data.id
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
