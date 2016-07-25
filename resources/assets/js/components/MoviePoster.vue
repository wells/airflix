<template>
  <li>
    <a :class="{ active : isActive }" @click.prevent="selectPoster()">
      <img :src="poster.attributes.file_url" />
    </a>
  </li>
</template>

<script>
import { 
  patchMovie 
} from '../vuex/actions/movies'

export default {
  name: 'MoviePoster',
  props: ['movie', 'poster'],
  vuex: {
  	actions: {
      patchMovie
    }
  },
  methods: {
    selectPoster: function () {
      if (this.isActive) {
        return;
      }

      const data = {
        'data': {
          'type': 'movies',
          'id': this.movie.id,
          'attributes': {
            'poster_path': this.poster.attributes.file_path
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
      var currentPoster = this.movie.attributes.poster_url
      var poster = this.poster.attributes.file_path
      return currentPoster.indexOf(poster) > -1
    }
  }
}
</script>