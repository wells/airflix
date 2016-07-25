<template>
  <li>
    <a :class="{ active : isActive }" @click.prevent="selectBackdrop()">
      <img :src="backdrop.attributes.file_url" />
    </a>
  </li>
</template>

<script>
import { 
  patchMovie 
} from '../vuex/actions/movies'

export default {
  name: 'MovieBackdrop',
  props: ['movie', 'backdrop'],
  vuex: {
  	actions: {
      patchMovie
    }
  },
  methods: {
    selectBackdrop: function () {
      if (this.isActive) {
        return;
      }

      const data = {
        'data': {
          'type': 'movies',
          'id': this.movie.id,
          'attributes': {
            'backdrop_path': this.backdrop.attributes.file_path
          }
        }
      }

      this.patchMovie(
        this.movie.id, data
      )
    }
  },
  computed: {
    isActive: function () {
      var currentBackdrop = this.movie.attributes.backdrop_url
      var backdrop = this.backdrop.attributes.file_path
      return currentBackdrop.indexOf(backdrop) > -1
    }
  }
}
</script>