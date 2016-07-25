<template>
<div class="settings">
  <spinner v-show="$loadingRouteData" transition="loading"></spinner>

  <div v-if="!$loadingRouteData">
    <h1>Settings</h1>

    <h2>
      <i class="material-icons">&#xE5D5;</i>
      Refresh
    </h2>

    <a class="button" @click.prevent="refreshNew">
      <i class="material-icons">&#xE2CC;</i> Refresh New
    </a>
    <a class="button" @click.prevent="refreshAll">
      <i class="material-icons">&#xE617;</i> Refresh All
    </a>

    <h2>
      <i class="material-icons">&#xE889;</i>
      History
    </h2>

    <a class="button" @click.prevent="clearToday">
      <i class="material-icons">&#xE8DF;</i> Clear Today
    </a>
    <a class="button" @click.prevent="clearAll">
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
    <input class="form-control" placeholder="Movies Folder (i.e. /movies)" :value="settings.attributes.movies_folder" @input="setMoviesFolder">
    <label>Shows Folder</label>
    <input class="form-control" placeholder="Shows Folder (i.e. /shows)" :value="settings.attributes.shows_folder" @input="setShowsFolder">
    <a class="button" @click.prevent="patchFolders">
      <i class="material-icons">&#xE2C7;</i> Update Folders
    </a>
  </div>
</div>
</template>

<script>
import {
  clearAll,
  clearToday,
  getSettings,
  patchSettings,
  refreshAll,
  refreshNew,
  setMoviesFolder,
  setShowsFolder
} from '../vuex/actions/settings'
import Spinner from './Spinner.vue'

export default {
  name: 'Settings',
  components: {
    Spinner 
  },
  vuex: {
    getters: {
      settings: ({ settings }) => settings.current
    },
    actions: {
      clearAll,
      clearToday,
      getSettings,
      patchSettings,
      refreshAll,
      refreshNew,
      setMoviesFolder,
      setShowsFolder
    }
  },
  route: {
    data (transition) {
      this.getSettings(transition)
    }
  },
  methods: {
    patchFolders: function () {
      const data = {
        'data': this.settings,
      }

      this.patchSettings(data)
    }
  }
}
</script>