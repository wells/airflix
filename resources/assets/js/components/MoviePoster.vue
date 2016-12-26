<template>
  <li>
    <a :class="{ active : isActive }" 
        @click.prevent="selectPoster()">
      <img :src="poster.attributes.file_url" />
    </a>
  </li>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'MoviePoster',

  props: ['movie', 'poster'],

  methods: {
    ...mapActions([
      'patchMovie'
    ]),

    selectPoster: function () {
      if (this.isActive) {
        return;
      }

      let payload = {
        url: '/api/movies/' + this.movie.id,
        json: {
          data: {
            type: 'movies',
            id: this.movie.id,
            attributes: {
              poster_path: this.poster.attributes.file_path
            }
          }
        }
      }

      this.patchMovie(payload)
    }
  },

  computed: {
    isActive: function () {
      let currentPoster = this.movie.attributes.poster_url
      let poster = this.poster.attributes.file_path

      return currentPoster.indexOf(poster) > -1
    }
  }
}
</script>