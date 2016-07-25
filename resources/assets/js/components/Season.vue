<template>
<div class="season">
  <spinner v-show="$loadingRouteData" transition="loading"></spinner>

  <div v-if="!$loadingRouteData">
    <img :src="show.attributes.backdrop_url" />

    <h1>{{ show.attributes.name }} &ndash; {{ season.attributes.name }}</h1>

    <a class="button" v-link="{ path: '/shows/' + show.id }">
      <i class="material-icons">&#xE5C4;</i> {{ show.attributes.name }}
    </a>

    <div class="overview" v-if="season.attributes.overview">
      <h2>
        <i class="material-icons">&#xE0B7;</i>
        Overview
      </h2>

      <img v-if="season.attributes.poster_url" :src="season.attributes.poster_url" />

      <p>
      {{ season.attributes.overview }}
      </p>
    </div>

    <h2>
      <i class="material-icons">&#xE04A;</i>
      Episodes
    </h2>

    <ul class="summaries" v-if="season.relationships.episodes">
      <episode v-for="episode in episodes" :episode="episode"></episode>
    </ul>

    <div v-if="season.relationships.views">
      <h2>
        <i class="material-icons">&#xE922;</i>
        Stats
      </h2>

      <ul class="tags">
        <li>
          <span class="key">Overall Views:</span>&nbsp;&nbsp;
          {{ season.attributes.total_views }}
        </li>
        <li>
          <span class="key">Last 12 Months:</span>&nbsp;&nbsp;
          {{ totalViewsLastYear }}
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
import Episode from './Episode.vue'
import MonthlyChart from './MonthlyChart.vue'
import Spinner from './Spinner.vue'
import { 
  selectSeason, 
  getSeason 
} from '../vuex/actions/seasons'

export default {
  name: 'Season',
  components: {
    Episode,
    MonthlyChart,
    Spinner
  },
  vuex: {
    getters: {
      season: ({ seasons }) => seasons.all.find(
        s => s.id === seasons.currentID
      ),
      episodes: ({ seasons, episodes }) => {
        const season = seasons.all.find(
          s => s.id === seasons.currentID
        )
        return season.relationships.episodes.data.map(
          ({ id }) => episodes.all.find(e => e.id === id)
        )
      },
      monthlyViews: ({ seasons, views }) => {
        const season = seasons.all.find(
          s => s.id === seasons.currentID
        )
        const seasonViews = season.relationships.views.data.map(
          ({ id }) => views.seasons.find(s => s.id === id)
        )

        // Create a moment.js range of the past 12 months
        const currentMonth = moment().startOf('month')
        const lastYear = currentMonth.clone().subtract(11, 'M')
        const range = moment.range(lastYear, currentMonth)
        const months = []

        // Create records for past 12 months and merge API data
        range.by('months', function(month) {
          const label = month.format('MM/YY')
          const view = seasonViews.find(
            s => s.attributes.label === label
          )
          months.push({
            id: label,
            total: view ? view.attributes.total : 0,
          })
        })

        return months
      },
      show: ({ seasons, shows }) => {
        const season = seasons.all.find(
          s => s.id === seasons.currentID
        )
        return shows.all.find(
          s => s.id === season.relationships.show.data.id
        )
      }
    },
    actions: {
      selectSeason,
      getSeason
    }
  },
  route: {
    data (transition) {
      this.selectSeason(transition)
      this.getSeason(transition)
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