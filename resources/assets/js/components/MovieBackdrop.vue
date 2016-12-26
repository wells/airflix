<template>
  <li>
    <a :class="{ active : isActive }" 
        @click.prevent="selectBackdrop()">
      <img :src="backdrop.attributes.file_url" />
    </a>
  </li>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'MovieBackdrop',

  props: ['movie', 'backdrop'],

  methods: {
    ...mapActions([
      'patchMovie'
    ]),

    selectBackdrop: function () {
      if (this.isActive) {
        return;
      }

      let payload = {
        url: '/api/movies/' +  this.movie.id,
        json: {
          data: {
            type: 'movies',
            id: this.movie.id,
            attributes: {
              backdrop_path: this.backdrop.attributes.file_path
            }
          }
        }
      }

      this.patchMovie(payload)
    }
  },

  computed: {
    isActive: function () {
      let currentBackdrop = this.movie.attributes.backdrop_url
      let backdrop = this.backdrop.attributes.file_path

      return currentBackdrop.indexOf(backdrop) > -1
    }
  }
}
</script>