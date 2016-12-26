<template>
<div class="settings">
  <transition name="loading">
    <spinner v-show="loadingRouteData"></spinner>
  </transition>

  <div v-if="!loadingRouteData">
    <h1>Settings</h1>

    <h2>
      <i class="material-icons">&#xE5D5;</i>
      Refresh
    </h2>

    <a class="button" @click.prevent="refreshNewFolders">
      <i class="material-icons">&#xE2CC;</i> Refresh New
    </a>
    <a class="button" @click.prevent="refreshAllFolders">
      <i class="material-icons">&#xE617;</i> Refresh All
    </a>

    <h2>
      <i class="material-icons">&#xE889;</i>
      History
    </h2>

    <a class="button" @click.prevent="clearHistoryToday">
      <i class="material-icons">&#xE8DF;</i> Clear Today
    </a>
    <a class="button" @click.prevent="clearHistory">
      <i class="material-icons">&#xE92B;</i> Clear All
    </a>

    <h2>
      <i class="material-icons">&#xE2C8;</i>
      Folders
    </h2>

    <p>
      Setting a folder path will refresh the database with 
      movies and/or shows found in that location.
    </p>

    <label>Movies Folder</label>

    <input class="form-control" 
        placeholder="Movies Folder (i.e. /movies)" 
        :value="settings.attributes.movies_folder" 
        @input="setMoviesFolder" />

    <label>Shows Folder</label>

    <input class="form-control" 
        placeholder="Shows Folder (i.e. /shows)" 
        :value="settings.attributes.shows_folder" 
        @input="setShowsFolder" />

    <a class="button" @click.prevent="patchFolders">
      <i class="material-icons">&#xE2C7;</i> Update Folders
    </a>
  </div>
</div>
</template>

<script>
import Spinner from './Spinner.vue'
import { mapActions } from 'vuex'

export default {
  name: 'Settings',

  components: {
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
      'clearAll',
      'clearToday',
      'getSettings',
      'patchSettings',
      'refreshAll',
      'refreshNew',
      'setMoviesFolder',
      'setShowsFolder',
      'loadingRoute'
    ]),

    clearHistory: function () {
      let payload = {
        url: '/api/settings/history?all'
      }

      this.clearAll(payload)
    },

    clearHistoryToday: function () {
      let payload = {
        url: '/api/settings/history'
      }

      this.clearToday(payload)
    },

    fetchData: function () {
      let payload = {
        url: '/api/settings'
      }

      this.getSettings(payload)
    },

    patchFolders: function () {
      let payload = {
        url: '/api/settings',
        json: {
          data: this.settings
        }
      }

      this.patchSettings(payload)
    },

    refreshAllFolders: function () {
      let payload = {
        url: '/api/settings/folders?all'
      }

      this.refreshAll(payload)
    },

    refreshNewFolders: function () {
      let payload = {
        url: '/api/settings/folders'
      }

      this.refreshNew(payload)
    }
  },

  computed: {
    loadingRouteData: function () {
      return this.$store.state.interfaces.loadingRouteData
    },

    settings: function ()  {
      return this.$store.state.settings.current
    }
  }
}
</script>