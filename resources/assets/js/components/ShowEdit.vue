<template>
<div class="show-edit">
  <spinner v-show="$loadingRouteData" transition="loading"></spinner>

  <div v-if="!$loadingRouteData">
    <h1>Edit Show</h1>

    <a class="button" v-link="{ path: '/shows/' + show.id }">
      <i class="material-icons">&#xE5C4;</i> {{ show.attributes.name }}
    </a>

    <div v-if="isResolved">
      <h2>Show Name</h2>

      <input class="form-control" placeholder="Name" :value="show.attributes.name" @input="patchName | debounce 1000">

      <ul class="tags">
        <li>
          <div class="key">Original Name:</div>
          <div class="value">{{ show.attributes.original_name }}</div>
        </li>
        <li>
          <div class="key">Folder Name:</div>
          <div class="value">{{ show.attributes.folder_name }}</div>
        </li>
      </ul>

      <h2>Posters</h2>
      <ul class="carousel posters" v-if="show.relationships.posters">
        <show-poster v-for="poster in posters" track-by="id" :show="show" :poster="poster">
        </show-poster>
      </ul>
    
      <h2>Backdrops</h2>
      <ul class="carousel backdrops" v-if="show.relationships.backdrops">
        <show-backdrop v-for="backdrop in backdrops" track-by="id" :show="show" :backdrop="backdrop">
        </show-backdrop>
      </ul>
    </div>

    <div v-if="!isResolved">
      <h2><i class="material-icons">&#xE002;</i> Please Resolve</h2>
      <p>
        Oops! We can't figure out which show this is. Please make a selection from the search results below.
      </p>
      <ul>
        <li>Folder Name: {{ show.attributes.folder_name }}</li>
      </ul>
    </div>

    <div v-if="show.relationships.results">
      <h2>Search Results</h2>
      <ul class="carousel posters">
        <show-result v-for="result in results" track-by="id" :show="show" :result="result">
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
import { 
  selectShow, 
  getShowWithResults, 
  patchShow
} from '../vuex/actions/shows'

export default {
  name: 'ShowEdit',
  components: {
    ShowBackdrop,
    ShowPoster,
    ShowResult,
    Spinner
  },
  vuex: {
    getters: {
      backdrops: ({ shows, images }) => {
        const show = shows.all.find(
          s => s.id === shows.currentID
        )
        return show.relationships.backdrops.data.map(
          ({ id }) => images.all.find(i => i.id === id)
        )
      },
      posters: ({ shows, images }) => {
        const show = shows.all.find(
          s => s.id === shows.currentID
        )
        return show.relationships.posters.data.map(
          ({ id }) => images.all.find(i => i.id === id)
        )
      },
      results: ({ shows, search }) => {
        const show = shows.all.find(
          s => s.id === shows.currentID
        )
        return show.relationships.results.data.map(
          ({ id }) => search.shows.find(s => s.id === id)
        )
      },
      show: ({ shows }) => shows.all.find(
        s => s.id === shows.currentID
      )
    },
    actions: {
      selectShow,
      getShowWithResults,
      patchShow
    }
  },
  route: {
    data: function(transition) {
      this.selectShow(transition)
      this.getShowWithResults(transition)
    }
  },
  methods: {
    patchName: function (event) {
      const data = {
        'data': {
          'type': 'shows',
          'id': this.show.id,
          'attributes': {
            'name': event.target.value
          }
        }
      }

      this.patchShow(this.show.id, data)
    }
  },
  computed: {
    isResolved: function () {
      return this.show.attributes.tmdb_show_id != 0
    },
  }
}
</script>