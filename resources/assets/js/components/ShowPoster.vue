<template>
  <li>
    <a :class="{ active : isActive }" @click.prevent="selectPoster()">
      <img style="width: 150px" :src="poster.attributes.file_url" />
    </a>
  </li>
</template>

<script>
import { 
  patchShow 
} from '../vuex/actions/shows'

export default {
  name: 'ShowPoster',
  props: ['show', 'poster'],
  vuex: {
  	actions: {
      patchShow
    }
  },
  methods: {
    selectPoster: function () {
      if (this.isActive) {
        return;
      }

      const data = {
        'data': {
          'type': 'shows',
          'id': this.show.id,
          'attributes': {
            'poster_path': this.poster.attributes.file_path
          }
        }
      }

      this.patchShow(
        this.show.id, data
      )
    }
  },
  computed: {
    isActive: function () {
      var currentPoster = this.show.attributes.poster_url
      var poster = this.poster.attributes.file_path
      return currentPoster.indexOf(poster) > -1
    }
  }
}
</script>