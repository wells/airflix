<template>
<div class="shows">
  <transition name="loading">
    <spinner v-show="loadingRouteData"></spinner>
  </transition>

  <!-- search -->
  <div class="search">
    <div class="fields">
      <input class="form-control" 
          placeholder="Keywords" 
          :value="keywords" 
          @input="filterShowKeywords" />

      <div class="dropdown order">
        <select class="form-control" 
            :value="selectedOrder" 
            @change="filterShowOrder">
          <option v-for="option in orders" 
              :value="option.id">
            {{ option.name }}
          </option>
        </select>
      </div>

      <div class="dropdown genres">
        <select class="form-control" 
            :value="selectedGenre"
            @change="filterShowGenres">
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
    <h1>Shows</h1>
    <div class="cards">
      <show-card v-for="show in shows" 
          :key="show.id" 
          :show="show">
      </show-card>
    </div>
  </div>

  <mugen-scroll :handler="loadMore" 
      :should-handle="!loading">
  </mugen-scroll>
</div>
</template>

<script>
import MugenScroll from 'vue-mugen-scroll'
import ShowCard from './ShowCard.vue'
import Spinner from './Spinner.vue'
import { mapActions } from 'vuex'

export default {
  name: 'Shows',

  components: {
    MugenScroll,
    ShowCard,
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
      'getShows',
      'filterGenres',
      'filterKeywords',
      'filterOrder',
      'loadingRoute'
    ]),

    fetchData: _.debounce(
      function (payloadUrl) {
        if(! payloadUrl) {
          payloadUrl = '/api/shows' + this.$store.state.filters.queryShows
        }

        let payload = {
          url: payloadUrl
        }

        this.getShows(payload)
      }, 
      250
    ),

    filterShowGenres: function (event) {
      this.filterGenres(event)
      this.fetchData()
    },

    filterShowKeywords: function (event) {
      this.filterKeywords(event)
      this.fetchData()
    },

    filterShowOrder: function (event) {
      this.filterOrder(event)
      this.fetchData()
    },

    filterReset: function () {
      this.clearFilters()
      this.fetchData()
    },

    loadMore: function() {
      if (this.links.next && !this.loading && !this._inactive) {
        this.fetchData(this.links.next)
      }
    },
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
      return this.$store.state.shows.links
    },

    loading: function () {
      return this.$store.state.shows.loading
    },

    loadingRouteData: function () {
      return this.$store.state.interfaces.loadingRouteData
    },

    selectedGenre: function () {
      return this.$store.state.filters.selectedGenre
    },

    selectedOrder: function () {
      return this.$store.state.filters.selectedOrder
    },

    shows: function () {
      let state = this.$store.state

      let keywords = state.filters.keywords
      let selectedGenre = state.filters.selectedGenre
      let column = state.filters.attributes.shows
      let direction = state.filters.direction

      return _.chain(state.shows.all)
        // Filter by keywords
        .filter(function (item) {
          let searchRegex = new RegExp(keywords, 'i')
          return searchRegex.test(item.attributes.name)
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
  }
}
</script>