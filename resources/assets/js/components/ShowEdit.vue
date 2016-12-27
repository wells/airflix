<template>
<div class="show-edit">
  <transition name="loading">
    <spinner v-show="loadingRouteData"></spinner>
  </transition>

  <div v-if="!loadingRouteData">
    <h1>Edit Show</h1>

    <router-link class="button" 
        :to="{ path: '/shows/' + show.id }">
      <i class="material-icons">&#xE5C4;</i> {{ show.attributes.name }}
    </router-link>

    <div v-if="isResolved">
      <h2>Show Name</h2>

      <input class="form-control" 
          placeholder="Name" 
          :value="show.attributes.name" 
          @input="patchName" />

      <ul class="tags">
        <li>
          <div class="key">Original Name:</div>
          <div class="value">
            {{ show.attributes.original_name }}
          </div>
        </li>
        <li>
          <div class="key">Folder Name:</div>
          <div class="value">
            {{ show.attributes.folder_name }}
          </div>
        </li>
      </ul>

      <h2>Posters</h2>
      <ul class="carousel posters" v-if="show.relationships.posters">
        <show-poster v-for="poster in posters" 
            :key="poster.id" 
            :show="show" 
            :poster="poster">
        </show-poster>
      </ul>
    
      <h2>Backdrops</h2>
      <ul class="carousel backdrops" v-if="show.relationships.backdrops">
        <show-backdrop v-for="backdrop in backdrops" 
            :key="backdrop.id" 
            :show="show" 
            :backdrop="backdrop">
        </show-backdrop>
      </ul>
    </div>

    <div v-if="!isResolved">
      <h2>
        <i class="material-icons">&#xE002;</i> Please Resolve
      </h2>
      <p>
        Oops! We can't figure out which show this is. Please make a selection 
        from the search results below.
      </p>
      <ul class="tags">
        <li>
          <div class="key">Folder Name:</div>
          <div class="value">
            {{ show.attributes.folder_name }}
          </div>
        </li>
      </ul>
    </div>

    <div v-if="show.relationships.results">
      <h2>Search Results</h2>
      <ul class="carousel posters">
        <show-result v-for="result in results" 
            :key="result.id" 
            :show="show" 
            :result="result">
        </show-result>
      </ul>
    </div>
  </div>
  </div>
</div>
</template>

<script>
import ShowBackdrop from './ShowBackdrop.vue'
import ShowPoster from './ShowPoster.vue'
import ShowResult from './ShowResult.vue'
import Spinner from './Spinner.vue'
import { mapActions } from 'vuex'

export default {
  name: 'ShowEdit',

  components: {
    ShowBackdrop,
    ShowPoster,
    ShowResult,
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
      'getShowWithResults',
      'loadingRoute',
      'patchShow'
    ]),

    fetchData: function () {
      let payload = {
        id: this.$route.params.id,
        url: '/api/shows/' + this.$route.params.id + 
          '?include=backdrops,posters,results'
      }

      this.getShowWithResults(payload)
    },

    patchName: _.debounce(
      function (event) {
        let payload = {
          url: '/api/shows/' + this.show.id,
          json: {
            data: {
              type: 'shows',
              id: this.show.id,
              attributes: {
                name: event.target.value
              }
            }
          }
        }

        this.patchShow(payload)
      },
      500
    )
  },

  computed: {
    backdrops: function () {
      let state = this.$store.state
      let show = state.shows.all.find(
        s => s.id == state.shows.currentID
      )

      return show.relationships.backdrops.data.map(
        ({ id }) => state.images.all.find(i => i.id == id)
      )
    },

    isResolved: function () {
      return this.show.attributes.tmdb_show_id != 0
    },

    loadingRouteData: function () {
      return this.$store.state.interfaces.loadingRouteData
    },

    posters: function () {
      let state = this.$store.state
      let show = state.shows.all.find(
        s => s.id == state.shows.currentID
      )

      return show.relationships.posters.data.map(
        ({ id }) => state.images.all.find(i => i.id == id)
      )
    },

    results: function () {
      let state = this.$store.state
      let show = state.shows.all.find(
        s => s.id == state.shows.currentID
      )

      return show.relationships.results.data.map(
        ({ id }) => state.search.shows.find(s => s.id == id)
      )
    },

    show: function () { 
      let state = this.$store.state

      return state.shows.all.find(
        s => s.id == state.shows.currentID
      )
    }
  }
}
</script>