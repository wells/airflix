<template>
  <li>
    <a :class="{ active : isActive }" 
        @click.prevent="selectResult()">
      <img :src="result.attributes.poster_url" />
      <span>{{ result.attributes.title }}</span>
    </a>
  </li>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'MovieResult',

  props: ['movie', 'result'],

  methods: {
    ...mapActions([
      'patchMovie'
    ]),

    selectResult: function () {
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
              tmdb_movie_id: this.result.id
            }
          }
        },
        redirect: '/movies/' + this.movie.id
      }

      this.patchMovie(payload)
    }
  },

  computed: {
    isActive: function () {
      return this.result.id == this.movie.attributes.tmdb_movie_id
    }
  }
}
</script>