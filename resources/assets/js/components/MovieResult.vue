<template>
  <li>
    <a :class="{ active : isActive }" @click.prevent="selectResult()">
      <img :src="result.attributes.poster_url" />
      <span>{{ result.attributes.title }}</span>
    </a>
  </li>
</template>

<script>
import { 
  patchMovie 
} from '../vuex/actions/movies'

export default {
  name: 'MovieResult',
  props: ['movie', 'result'],
  vuex: {
  	actions: {
      patchMovie
    }
  },
  methods: {
    selectResult: function () {
      if (this.isActive) {
        return;
      }

      const data = {
        'data': {
          'type': 'movies',
          'id': this.movie.id,
          'attributes': {
            'tmdb_movie_id': this.result.id
          }
        }
      }

      this.patchMovie(
        this.movie.id, data, this.$route.router
      )
    }
  },
  computed: {
    isActive: function () {
      return this.result.id == this.movie.attributes.tmdb_movie_id
    }
  }
}
</script>