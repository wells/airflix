<template>
<div class="shows" v-infinite-scroll="loadMore()" infinite-scroll-disabled="loading" infinite-scroll-distance="200">

  <spinner v-show="$loadingRouteData" transition="loading"></spinner>

  <!-- search -->
  <div class="search">
    <div class="fields">
      <input class="form-control" placeholder="Keywords" :value="keywords" @input="filterShowKeywords | debounce 250">

      <div class="dropdown order">
        <select class="form-control" :value="order" @change="filterShowOrder">
          <option v-for="option in orders" :value="option.id">{{ option.name }}</option>
        </select>
      </div>

      <div class="dropdown genres">
        <select class="form-control" :value="selectedGenre" @change="filterShowGenres">
          <option value="">All Genres</option>
          <option v-for="option in genres | filterByHasShows | orderBy 'attributes.name'" :value="option.id">{{ option.attributes.name }}</option>
        </select>
      </div>
    </div>
    <div class="clear">
      <a class="button" @click.prevent="filterReset">Clear</a>
    </div>
  </div>

  <!-- results -->
  <div class="results">
    <h1>Shows</h1>
    <div class="cards">
      <show-card  v-for="show in shows | filterBy keywords in 'attributes.name'| filterByGenre selectedGenre | orderBy column direction" track-by="id" :show="show">
      </show-card>
    </div>
  </div>
</div>
</template>

<script>
import ShowCard from './ShowCard.vue'
import Spinner from './Spinner.vue'
import { 
  getShows 
} from '../vuex/actions/shows'
import {
  clearFilters,
  clearGenresFilter, 
  filterGenres, 
  filterKeywords, 
  filterOrder 
} from '../vuex/actions/filters'

export default {
  name: 'Shows',
  components: { 
    ShowCard,
    Spinner
  },
  ready: function () {
    this.$watch('links.next', function () {
      // Automatically load paginated results
      setTimeout(() => {
        if(this.links.next && !this.loading) {
          this.getShows(this.links.next)
        }
      }, 1000);
    }, { deep: true })
  },
  vuex: {
    getters: {
      column: ({ filters }) => filters.attributes.shows,
      direction: ({ filters }) => filters.direction,
      genres: ({ genres }) => genres.all,
      keywords: ({ filters }) => filters.keywords,
      links: ({ shows }) => shows.links,
      loading: ({ movies }) => movies.loading,
      order: ({ filters }) => filters.order,
      orders: ({ filters }) => filters.orders,
      query: ({ filters }) => filters.queryShows,
      selectedGenre: ({ filters }) => filters.genre,
      shows: ({ shows }) => shows.all
    },
    actions: {
      clearFilters,
      clearGenresFilter,
      getShows,
      filterGenres,
      filterKeywords,
      filterOrder
    }
  },
  route: {
    data (transition) {
      this.clearGenresFilter()
      this.getShows(this.url, transition)
    }
  },
  methods: {
    loadMore: function() {
      if (this.links.next && !this.loading && !this._inactive) {
        this.getShows(this.links.next)
      }
    },
    filterShowGenres: function (event) {
      this.filterGenres(event)
      this.getShows(this.url)
    },
    filterShowKeywords: function (event) {
      this.filterKeywords(event)
      this.getShows(this.url)
    },
    filterShowOrder: function (event) {
      this.filterOrder(event)
      this.getShows(this.url)
    },
    filterReset: function () {
      this.clearFilters()
      this.getShows(this.url)
    }
  },
  computed: {
    url: function() {
      return '/api/shows' + this.query
    }
  }
}
</script>